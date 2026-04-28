<?php

namespace Database\Factories;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\User;
use App\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Approval>
 */
class ApprovalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'approver_id' => User::factory(),
            'step_id' => WorkflowStep::factory(),
            'approval_status' => $this->faker->randomElement(['Pending', 'Approved', 'Rejected']),
            'notes' => $this->faker->optional()->sentence(),
            'signature_image' => $this->faker->optional()->imagePath(),
            'qr_code' => $this->faker->optional()->imagePath(),
            'approved_at' => $this->faker->dateTime(),
            'attempt' => $this->faker->numberBetween(1, 3),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Pending',
            'notes' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Approved',
            'notes' => $this->faker->sentence(),
            'signature_image' => $this->faker->imagePath(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Rejected',
            'notes' => $this->faker->sentence(),
        ]);
    }
}