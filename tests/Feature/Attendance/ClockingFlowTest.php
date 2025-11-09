<?php

namespace Tests\Feature\Attendance;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClockingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_clock_in_and_record_is_saved(): void
    {
        $now = Carbon::create(2024, 5, 1, 9, 0, 0);
        Carbon::setTestNow($now);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/attendance')
            ->post(route('attendance.clock-in'));

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('success', '出勤打刻が完了しました。');

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'clock_in' => '09:00:00',
        ]);

        Carbon::setTestNow();
    }

    public function test_user_cannot_clock_in_more_than_once_per_day(): void
    {
        $now = Carbon::create(2024, 5, 1, 9, 0, 0);
        Carbon::setTestNow($now);

        $user = User::factory()->create();

        AttendanceRecord::factory()->for($user)->create([
            'date' => $now->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        $response = $this->actingAs($user)
            ->from('/attendance')
            ->post(route('attendance.clock-in'));

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('error', '既に出勤打刻が記録されています。');
        $this->assertEquals(1, AttendanceRecord::count());

        Carbon::setTestNow();
    }

    public function test_clock_in_time_is_visible_on_attendance_list(): void
    {
        $now = Carbon::create(2024, 5, 2, 9, 15);
        Carbon::setTestNow($now);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/attendance')
            ->post(route('attendance.clock-in'));

        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertSee('09:15');

        Carbon::setTestNow();
    }

    public function test_user_can_clock_out_and_work_time_is_calculated(): void
    {
        $day = Carbon::create(2024, 5, 3, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        AttendanceRecord::factory()->for($user)->withoutClockOut()->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        $clockOut = $day->copy()->setTime(18, 0, 0);
        Carbon::setTestNow($clockOut);

        $response = $this->actingAs($user)
            ->from('/attendance')
            ->post(route('attendance.clock-out'));

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('success', 'お疲れ様でした。退勤打刻が完了しました。');

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'clock_out' => '18:00:00',
            'work_time' => '09:00:00',
        ]);

        Carbon::setTestNow();
    }

    public function test_clock_out_time_is_visible_on_attendance_list(): void
    {
        $day = Carbon::create(2024, 5, 4, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        AttendanceRecord::factory()->for($user)->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'break_time' => '01:00:00',
            'work_time' => '08:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2024-05');
        $response->assertSee('18:00');

        Carbon::setTestNow();
    }

    public function test_user_can_start_break_while_working(): void
    {
        $day = Carbon::create(2024, 5, 5, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        AttendanceRecord::factory()->for($user)->withoutClockOut()->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        $breakStart = $day->copy()->setTime(12, 0);
        Carbon::setTestNow($breakStart);

        $response = $this->actingAs($user)
            ->from('/attendance')
            ->post(route('attendance.break-start'));

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('success', '休憩開始しました。');

        $this->assertDatabaseHas('breaks', [
            'break_start' => '12:00:00',
            'break_end' => null,
        ]);

        Carbon::setTestNow();
    }

    public function test_user_can_end_break_and_duration_is_recorded(): void
    {
        $day = Carbon::create(2024, 5, 6, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->withoutClockOut()->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        BreakRecord::factory()->for($record)->create([
            'break_start' => '12:00:00',
            'break_end' => null,
            'break_duration' => 0,
        ]);

        $breakEnd = $day->copy()->setTime(12, 30);
        Carbon::setTestNow($breakEnd);

        $response = $this->actingAs($user)
            ->from('/attendance')
            ->post(route('attendance.break-end'));

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('success', '休憩終了しました。');

        $this->assertDatabaseHas('breaks', [
            'attendance_record_id' => $record->id,
            'break_start' => '12:00:00',
            'break_end' => '12:30:00',
            'break_duration' => 1800,
        ]);

        Carbon::setTestNow();
    }

    public function test_user_can_take_multiple_breaks_in_a_day(): void
    {
        $day = Carbon::create(2024, 5, 7, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->withoutClockOut()->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        Carbon::setTestNow($day->copy()->setTime(12, 0));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-start'));

        Carbon::setTestNow($day->copy()->setTime(12, 15));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-end'));

        Carbon::setTestNow($day->copy()->setTime(15, 0));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-start'));

        $this->assertEquals(2, $record->breaks()->count());
        $this->assertDatabaseHas('breaks', [
            'attendance_record_id' => $record->id,
            'break_start' => '15:00:00',
            'break_end' => null,
        ]);

        Carbon::setTestNow();
    }

    public function test_user_can_finish_multiple_breaks_in_a_day(): void
    {
        $day = Carbon::create(2024, 5, 8, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->for($user)->withoutClockOut()->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        Carbon::setTestNow($day->copy()->setTime(11, 30));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-start'));
        Carbon::setTestNow($day->copy()->setTime(11, 45));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-end'));

        Carbon::setTestNow($day->copy()->setTime(15, 15));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-start'));
        Carbon::setTestNow($day->copy()->setTime(15, 45));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-end'));

        $this->assertEquals(2, BreakRecord::where('attendance_record_id', $record->id)->count());
        $this->assertEquals(0, BreakRecord::where('attendance_record_id', $record->id)->whereNull('break_end')->count());

        Carbon::setTestNow();
    }

    public function test_break_time_summary_is_visible_in_attendance_list(): void
    {
        $day = Carbon::create(2024, 5, 9, 9);
        Carbon::setTestNow($day);

        $user = User::factory()->create();
        AttendanceRecord::factory()->for($user)->withoutClockOut()->create([
            'date' => $day->toDateString(),
            'clock_in' => '09:00:00',
        ]);

        Carbon::setTestNow($day->copy()->setTime(12, 0));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-start'));
        Carbon::setTestNow($day->copy()->setTime(12, 30));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.break-end'));

        Carbon::setTestNow($day->copy()->setTime(18, 0));
        $this->actingAs($user)->from('/attendance')->post(route('attendance.clock-out'));

        $response = $this->actingAs($user)->get('/attendance/list?month=2024-05');
        $response->assertSee('0:30');

        Carbon::setTestNow();
    }
}
