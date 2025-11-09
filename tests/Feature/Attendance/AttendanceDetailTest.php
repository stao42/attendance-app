<?php

namespace Tests\Feature\Attendance;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_detail_displays_logged_in_user_name(): void
    {
        $user = User::factory()->create(['name' => '山田太郎']);
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-20',
        ]);

        $response = $this->actingAs($user)->get("/attendance/detail/{$record->id}");

        $response->assertOk();
        $response->assertSee('山田太郎');
    }

    public function test_attendance_detail_displays_selected_date(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-21',
        ]);

        $response = $this->actingAs($user)->get("/attendance/detail/{$record->id}");

        $response->assertSee('2024年');
        $response->assertSee('5月21日');
    }

    public function test_attendance_detail_shows_clock_in_and_out_times(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-22',
            'clock_in' => '09:05:00',
            'clock_out' => '18:10:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/detail/{$record->id}");

        $response->assertSee('value="09:05"', false);
        $response->assertSee('value="18:10"', false);
    }

    public function test_attendance_detail_displays_break_times(): void
    {
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->create([
            'date' => '2024-05-23',
        ]);

        BreakRecord::factory()->for($record)->create([
            'break_start' => '12:00:00',
            'break_end' => '12:30:00',
        ]);

        BreakRecord::factory()->for($record)->create([
            'break_start' => '15:00:00',
            'break_end' => '15:10:00',
        ]);

        $response = $this->actingAs($user)->get("/attendance/detail/{$record->id}");

        $response->assertSee('value="12:00"', false);
        $response->assertSee('value="12:30"', false);
        $response->assertSee('value="15:00"', false);
        $response->assertSee('value="15:10"', false);
    }
}
