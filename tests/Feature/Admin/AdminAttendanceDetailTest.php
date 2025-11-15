<?php

namespace Tests\Feature\Admin;

use App\Models\AttendanceRecord;
use App\Models\StampCorrectionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_attendance_detail_displays_selected_record(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['name' => '高橋健']);
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-12',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.attendance.detail', $record->id));

        $response->assertOk();
        $response->assertSee('高橋健');
        $response->assertSee('2024年');
        $response->assertSee('5月12日');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    public function test_admin_update_attendance_validates_clock_out_order(): void
    {
        $admin = $this->createAdmin();
        $record = AttendanceRecord::factory()->for(User::factory()->create())->create([
            'date' => '2024-05-13',
            'clock_in' => '09:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.detail', $record->id))
            ->post(route('admin.attendance.update', $record->id), [
                'clock_in' => '10:00',
                'clock_out' => '09:00',
                'notes' => '確認',
            ]);

        $response->assertRedirect(route('admin.attendance.detail', $record->id));
        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_admin_can_update_attendance_with_breaks(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-14',
            'clock_in' => '09:00:00',
            'clock_out' => '17:00:00',
            'break_time' => '00:00:00',
            'work_time' => '08:00:00',
            'notes' => '旧メモ',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.attendance.update', $record->id), [
                'clock_in' => '08:30',
                'clock_out' => '18:00',
                'notes' => '更新後のメモ',
                'break_starts' => ['12:00', '15:00'],
                'break_ends' => ['12:30', '15:10'],
            ]);

        $response->assertRedirect(route('admin.attendance.list'));

        $this->assertDatabaseHas('attendance_records', [
            'id' => $record->id,
            'clock_in' => '08:30',
            'clock_out' => '18:00',
            'notes' => '更新後のメモ',
        ]);

        $this->assertDatabaseCount('breaks', 2);
    }

    public function test_admin_cannot_update_attendance_when_pending_request_exists(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-15',
        ]);

        StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.detail', $record->id))
            ->post(route('admin.attendance.update', $record->id), [
                'clock_in' => '08:00',
                'clock_out' => '17:00',
                'notes' => '編集不可',
            ]);

        $response->assertRedirect(route('admin.attendance.detail', $record->id));
        $response->assertSessionHas('error', '承認待ちのため修正はできません。');
    }

    public function test_admin_update_attendance_validates_break_start_before_clock_out(): void
    {
        $admin = $this->createAdmin();
        $record = AttendanceRecord::factory()->for(User::factory()->create())->create([
            'date' => '2024-05-16',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.detail', $record->id))
            ->post(route('admin.attendance.update', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'notes' => 'テスト',
                'break_starts' => ['19:00'],
                'break_ends' => ['19:30'],
            ]);

        $response->assertRedirect(route('admin.attendance.detail', $record->id));
        $response->assertSessionHasErrors([
            'break_starts.0' => '休憩時間が不適切な値です',
        ]);
    }

    public function test_admin_update_attendance_validates_break_end_before_clock_out(): void
    {
        $admin = $this->createAdmin();
        $record = AttendanceRecord::factory()->for(User::factory()->create())->create([
            'date' => '2024-05-17',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.detail', $record->id))
            ->post(route('admin.attendance.update', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'notes' => 'テスト',
                'break_starts' => ['12:00'],
                'break_ends' => ['19:00'],
            ]);

        $response->assertRedirect(route('admin.attendance.detail', $record->id));
        $response->assertSessionHasErrors([
            'break_ends.0' => '休憩時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_admin_update_attendance_requires_notes(): void
    {
        $admin = $this->createAdmin();
        $record = AttendanceRecord::factory()->for(User::factory()->create())->create([
            'date' => '2024-05-18',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.detail', $record->id))
            ->post(route('admin.attendance.update', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'notes' => '',
            ]);

        $response->assertRedirect(route('admin.attendance.detail', $record->id));
        $response->assertSessionHasErrors([
            'notes' => '備考を記入してください',
        ]);
    }
}
