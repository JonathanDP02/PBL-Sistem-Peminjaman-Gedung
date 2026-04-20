<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'room_id' => \App\Models\Room::factory(),
            'workflow_id' => \App\Models\Workflow::factory(),
            'event_name' => $this->faker->words(3, asText: true),
            'event_description' => $this->faker->sentence(),
            'booking_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'current_step' => 1,
            'status' => 'Pending',
            'revision_count' => 0,
        ];
    }
}
