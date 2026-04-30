<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Workflow;
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
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'workflow_id' => Workflow::factory(),
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

    /**
     * State: Pending approval
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending',
            'current_step' => 1,
        ]);
    }

    /**
     * State: Approved
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Approved',
            'current_step' => 2,
        ]);
    }

    /**
     * State: Rejected
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Rejected',
            'revision_count' => $this->faker->numberBetween(1, 3),
        ]);
    }

    /**
     * State: Completed
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Completed',
            'current_step' => 3,
        ]);
    }
}
