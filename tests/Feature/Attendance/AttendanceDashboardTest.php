<?php

namespace Tests\Feature\Attendance;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_page_displays_current_datetime(): void
    {
        $now = Carbon::create(2024, 5, 10, 9, 30, 0);
        Carbon::setTestNow($now);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attendance');

        $weekday = ['日', '月', '火', '水', '木', '金', '土'][$now->dayOfWeek];

        $response->assertOk();
        $response->assertSee(sprintf('%s(%s)', $now->format('Y年m月d日'), $weekday));
        $response->assertSee($now->format('H:i'));

        Carbon::setTestNow();
    }

    public function test_status_is_off_duty_when_no_record_exists(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 5, 1, 8, 0));

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertOk();
        $response->assertSee('勤務外');

        Carbon::setTestNow();
    }

    public function test_status_is_working_when_clocked_in(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 5, 1, 12));

        $user = User::factory()->create();
        AttendanceRecord::factory()
            ->for($user)
            ->withoutClockOut()
            ->create([
                'date' => Carbon::today()->format('Y-m-d'),
                'clock_in' => '09:00:00',
            ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('出勤中');

        Carbon::setTestNow();
    }

    public function test_status_is_on_break_when_latest_break_has_no_end(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 5, 1, 12, 30));

        $user = User::factory()->create();
        $record = AttendanceRecord::factory()
            ->for($user)
            ->withoutClockOut()
            ->create([
                'date' => Carbon::today()->format('Y-m-d'),
                'clock_in' => '09:00:00',
            ]);

        BreakRecord::factory()->for($record)->create([
            'break_start' => '12:00:00',
            'break_end' => null,
            'break_duration' => 0,
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('休憩中');

        Carbon::setTestNow();
    }

    public function test_status_is_after_work_when_clock_out_exists(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 5, 1, 20));

        $user = User::factory()->create();
        AttendanceRecord::factory()->for($user)->create([
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'break_time' => '01:00:00',
            'work_time' => '08:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('退勤済');

        Carbon::setTestNow();
    }
}
