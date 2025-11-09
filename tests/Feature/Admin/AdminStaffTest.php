<?php

namespace Tests\Feature\Admin;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStaffTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_staff_list_displays_all_general_users(): void
    {
        $admin = $this->createAdmin();
        $userA = User::factory()->create(['name' => '社員A', 'email' => 'a@example.com']);
        $userB = User::factory()->create(['name' => '社員B', 'email' => 'b@example.com']);

        $response = $this->actingAs($admin)->get('/admin/staff/list');

        $response->assertOk();
        $response->assertSee('社員A');
        $response->assertSee('a@example.com');
        $response->assertSee('社員B');
        $response->assertSee('b@example.com');
    }

    public function test_staff_attendance_list_displays_selected_month_records(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['name' => 'スタッフ']);

        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-10',
            'clock_in' => '09:00:00',
        ]);
        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-06-01',
            'clock_in' => '10:00:00',
        ]);

        $response = $this->actingAs($admin)->get("/admin/attendance/staff/{$user->id}?month=2024-05");

        $response->assertOk();
        $response->assertSee('05/10');
        $response->assertDontSee('06/01');
        $response->assertSee('value="2024-04"', false);
        $response->assertSee('value="2024-06"', false);
    }

    public function test_staff_attendance_list_contains_detail_links(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-20',
        ]);

        $response = $this->actingAs($admin)->get("/admin/attendance/staff/{$user->id}?month=2024-05");

        $response->assertSee("/admin/attendance/{$record->id}");
        $response->assertSee('詳細');
    }
}
