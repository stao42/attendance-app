<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\BreakRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BreakRecord>
 */
class BreakRecordFactory extends Factory
{
    protected $model = BreakRecord::class;

    public function definition(): array
    {
        $start = Carbon::createFromTimestamp(fake()->dateTimeBetween('-1 day', 'now')->getTimestamp())
            ->setSecond(0);
        $end = (clone $start)->addMinutes(30);

        return [
            'attendance_record_id' => AttendanceRecord::factory(),
            'break_start' => $start->format('H:i:s'),
            'break_end' => $end->format('H:i:s'),
            'break_duration' => 1800,
        ];
    }
}
