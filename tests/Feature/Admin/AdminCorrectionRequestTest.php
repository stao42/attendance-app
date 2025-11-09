<?php

namespace Tests\Feature\Admin;

use App\Models\AttendanceRecord;
use App\Models\StampCorrectionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCorrectionRequestTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_request_list_shows_pending_and_approved_entries(): void
    {
        $admin = $this->createAdmin();
        $userA = User::factory()->create(['name' => '社員A']);
        $userB = User::factory()->create(['name' => '社員B']);

        $recordA = AttendanceRecord::factory()->for($userA)->create(['date' => '2024-05-01']);
        $recordB = AttendanceRecord::factory()->for($userB)->create(['date' => '2024-05-02']);

        StampCorrectionRequest::factory()->for($recordA, 'attendanceRecord')->for($userA)->create([
            'requested_notes' => 'Aの申請',
            'status' => 'pending',
        ]);

        StampCorrectionRequest::factory()->for($recordB, 'attendanceRecord')->for($userB)->create([
            'requested_notes' => 'Bの申請',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get('/stamp_correction_request/list');

        $response->assertOk();
        $response->assertSee('申請一覧');
        $response->assertSee('Aの申請');
        $response->assertSee('Bの申請');
        $response->assertSee('承認待ち');
        $response->assertSee('承認済み');
    }

    public function test_admin_can_view_request_detail(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['name' => '対象社員']);
        $record = AttendanceRecord::factory()->for($user)->create(['date' => '2024-05-03']);

        $request = StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'requested_clock_in' => '08:30:00',
            'requested_clock_out' => '18:30:00',
            'requested_notes' => '詳細確認',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.stamp_correction_request.approve.detail', $request->id));

        $response->assertOk();
        $response->assertSee('対象社員');
        $response->assertSee('08:30');
        $response->assertSee('18:30');
        $response->assertSee('詳細確認');
    }

    public function test_admin_can_approve_request(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-04',
            'clock_in' => '09:00:00',
            'clock_out' => '17:00:00',
            'notes' => '元の備考',
        ]);

        $request = StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'requested_clock_in' => '08:00:00',
            'requested_clock_out' => '18:00:00',
            'requested_notes' => '修正後の備考',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.stamp_correction_request.approve', $request->id));

        $response->assertRedirect(route('stamp_correction_request.list'));
        $response->assertSessionHas('success', '修正申請を承認しました。');

        $this->assertDatabaseHas('attendance_records', [
            'id' => $record->id,
            'clock_in' => '08:00:00',
            'clock_out' => '18:00:00',
            'notes' => '修正後の備考',
        ]);

        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $request->id,
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);
    }

    public function test_admin_can_reject_request(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create(['date' => '2024-05-05']);

        $request = StampCorrectionRequest::factory()->for($record, 'attendanceRecord')->for($user)->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.stamp_correction_request.reject', $request->id));

        $response->assertRedirect(route('stamp_correction_request.list'));
        $response->assertSessionHas('success', '修正申請を却下しました。');

        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $request->id,
            'status' => 'rejected',
            'approved_by' => $admin->id,
        ]);
    }

    public function test_non_admin_cannot_access_request_approval_routes(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $request = StampCorrectionRequest::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.stamp_correction_request.approve.detail', $request->id));
        $response->assertForbidden();
    }
}
