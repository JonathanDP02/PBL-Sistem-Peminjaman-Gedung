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
    protected $model = Approval::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'approver_id' => User::factory(),
            'step_id' => WorkflowStep::factory(),
            'approval_status' => 'Pending',
            'notes' => null,
            'signature_image' => null,
            'qr_code' => null,
            'approved_at' => now(),
            'attempt' => 1,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Pending',
            'notes' => null,
            'signature_image' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Approved',
            'notes' => null,
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Rejected',
            'notes' => $this->faker->sentence(),
        ]);
    }

    public function revising(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'Revising',
            'notes' => $this->faker->sentence(),
        ]);
    }
}
