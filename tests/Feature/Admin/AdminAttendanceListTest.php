<?php

namespace Tests\Feature\Admin;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_attendance_list_displays_all_users_for_selected_date(): void
    {
        $admin = $this->createAdmin();
        $userA = User::factory()->create(['name' => '田中一郎']);
        $userB = User::factory()->create(['name' => '鈴木花子']);

        $targetDate = '2024-05-10 00:00:00';

        AttendanceRecord::factory()->for($userA)->create([
            'date' => $targetDate,
            'clock_in' => '09:00:00',
        ]);
        AttendanceRecord::factory()->for($userB)->create([
            'date' => $targetDate,
            'clock_in' => '09:30:00',
        ]);
        AttendanceRecord::factory()->for($userA)->create([
            'date' => '2024-05-11 00:00:00',
            'clock_in' => '10:00:00',
        ]);

        $response = $this->actingAs($admin)->get('/admin/attendance/list?date=' . urlencode($targetDate));

        $response->assertOk();
        $response->assertSee('2024年5月10日の勤怠');
        $response->assertSee('田中一郎');
        $response->assertSee('鈴木花子');
        $response->assertDontSee('2024年5月11日');
    }

    public function test_admin_attendance_list_shows_previous_and_next_date_links(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/attendance/list?date=' . urlencode('2024-05-15 00:00:00'));

        $response->assertSee('value="2024-05-14"', false);
        $response->assertSee('value="2024-05-16"', false);
        $response->assertSee('2024/05/15');
    }

    public function test_non_admin_cannot_access_admin_attendance_list(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/attendance/list');

        $response->assertForbidden();
    }
}
