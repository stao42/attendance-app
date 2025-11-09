<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\StampCorrectionRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StampCorrectionRequest>
 */
class StampCorrectionRequestFactory extends Factory
{
    protected $model = StampCorrectionRequest::class;

    public function definition(): array
    {
        return [
            'attendance_record_id' => AttendanceRecord::factory(),
            'user_id' => User::factory(),
            'requested_clock_in' => '09:00:00',
            'requested_clock_out' => '18:00:00',
            'requested_notes' => fake()->realText(30),
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approved_at' => now(),
        ]);
    }
}
