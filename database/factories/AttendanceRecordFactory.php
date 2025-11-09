<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition(): array
    {
        $clockIn = fake()->dateTimeBetween('-1 month', 'now');
        $clockOut = (clone $clockIn)->modify('+8 hours');

        return [
            'user_id' => User::factory(),
            'date' => $clockIn->format('Y-m-d'),
            'clock_in' => $clockIn->format('H:i:s'),
            'clock_out' => $clockOut->format('H:i:s'),
            'break_time' => '01:00:00',
            'work_time' => '07:00:00',
            'notes' => fake()->sentence(),
        ];
    }

    /**
     * State for a record without a clock-out (still working).
     */
    public function withoutClockOut(): static
    {
        return $this->state(fn () => [
            'clock_out' => null,
            'work_time' => null,
            'break_time' => '00:00:00',
        ]);
    }

    /**
     * State for a record that only has a date (no clock-in yet).
     */
    public function withoutClockIn(): static
    {
        return $this->state(fn () => [
            'clock_in' => null,
            'clock_out' => null,
            'work_time' => null,
            'break_time' => '00:00:00',
        ]);
    }
}
