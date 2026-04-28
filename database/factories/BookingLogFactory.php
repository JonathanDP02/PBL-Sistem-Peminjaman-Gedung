<?php

namespace Database\Factories;

use App\Models\BookingLog;
use App\Models\Booking;
use App\Models\User;
use App\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingLog>
 */
class BookingLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'actor_id' => User::factory(),
            'step_id' => WorkflowStep::factory()->nullable(),
            'action' => $this->faker->randomElement([
                'CREATED', 'SUBMITTED', 'APPROVED', 'REJECTED', 'REVISED', 'FINALIZED', 'DOCUMENT_UPLOADED',
            ]),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}