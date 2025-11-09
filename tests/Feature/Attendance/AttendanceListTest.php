<?php

namespace Tests\Feature\Attendance;

use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_list_displays_all_records_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-10',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-11',
            'clock_in' => '09:30:00',
            'clock_out' => '18:15:00',
        ]);

        AttendanceRecord::factory()->for($otherUser)->create([
            'date' => '2024-05-12',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2024-05');

        $response->assertSee('05/10');
        $response->assertSee('05/11');
        $response->assertDontSee('05/12');
    }

    public function test_attendance_list_defaults_to_current_month(): void
    {
        $now = Carbon::create(2024, 4, 15);
        Carbon::setTestNow($now);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertOk();
        $response->assertSee($now->format('Y/m'));

        Carbon::setTestNow();
    }

    public function test_previous_month_filter_displays_previous_month_records(): void
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-04-15',
            'clock_in' => '09:00:00',
        ]);

        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-10',
            'clock_in' => '09:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2024-04');

        $response->assertSee('04/15');
        $response->assertDontSee('05/10');
    }

    public function test_next_month_filter_displays_following_month_records(): void
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-06-01',
            'clock_in' => '10:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2024-06');

        $response->assertSee('06/01');
    }

    public function test_attendance_list_contains_detail_link_for_each_record(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-20',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2024-05');

        $response->assertSee("/attendance/detail/{$record->id}");
        $response->assertSee('詳細');
    }
}
