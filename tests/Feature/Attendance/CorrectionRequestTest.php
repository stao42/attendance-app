<?php

namespace Tests\Feature\Attendance;

use App\Models\AttendanceRecord;
use App\Models\StampCorrectionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorrectionRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_correction_request_requires_clock_out_after_clock_in(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-24',
        ]);

        $response = $this->actingAs($user)
            ->from("/attendance/detail/{$record->id}")
            ->post(route('attendance.request-correction', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '08:00',
                'notes' => '退勤時刻を修正します',
            ]);

        $response->assertRedirect("/attendance/detail/{$record->id}");
        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_correction_request_requires_notes(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-25',
        ]);

        $response = $this->actingAs($user)
            ->from("/attendance/detail/{$record->id}")
            ->post(route('attendance.request-correction', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'notes' => '',
            ]);

        $response->assertRedirect("/attendance/detail/{$record->id}");
        $response->assertSessionHasErrors(['notes']);
    }

    public function test_user_can_submit_correction_request(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-26',
        ]);

        $response = $this->actingAs($user)
            ->post(route('attendance.request-correction', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'notes' => '残業時間を修正したいです',
            ]);

        $response->assertRedirect('/attendance/list');
        $response->assertSessionHas('success', '修正申請を送信しました。');

        $this->assertDatabaseHas('stamp_correction_requests', [
            'attendance_record_id' => $record->id,
            'user_id' => $user->id,
            'requested_clock_in' => '09:00',
            'requested_clock_out' => '18:00',
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_submit_request_when_pending_request_exists(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-27',
        ]);

        StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->from("/attendance/detail/{$record->id}")
            ->post(route('attendance.request-correction', $record->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'notes' => '再申請します',
            ]);

        $response->assertRedirect("/attendance/detail/{$record->id}");
        $response->assertSessionHas('error', '承認待ちのため修正はできません。');
    }

    public function test_request_list_displays_pending_and_approved_requests(): void
    {
        $user = User::factory()->create(['name' => '佐藤花子']);
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-28',
        ]);

        StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'requested_notes' => '遅刻しました',
            'status' => 'pending',
        ]);

        StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'requested_notes' => '早退しました',
            'status' => 'approved',
        ]);

        $otherUser = User::factory()->create();
        StampCorrectionRequest::factory()->for(
            AttendanceRecord::factory()->for($otherUser)->create(),
            'attendanceRecord'
        )->for($otherUser)->create([
            'requested_notes' => '他ユーザー',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/stamp_correction_request/list');

        $response->assertSee('承認待ち');
        $response->assertSee('承認済み');
        $response->assertSee('佐藤花子');
        $response->assertSee('遅刻しました');
        $response->assertSee('早退しました');
        $response->assertDontSee('他ユーザー');
    }

    public function test_request_list_contains_detail_links(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-29',
        ]);

        StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create();

        $response = $this->actingAs($user)->get('/stamp_correction_request/list');

        $response->assertSee("/attendance/detail/{$record->id}");
        $response->assertSee('詳細');
    }
}
