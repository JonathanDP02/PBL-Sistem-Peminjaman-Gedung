<?php

namespace Database\Factories;

use App\Models\BookingAttachment;
use App\Models\Booking;
use App\Models\User;
use App\Models\WorkflowRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingAttachment>
 */
class BookingAttachmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'requirement_id' => WorkflowRequirement::factory(),
            'uploader_id' => User::factory(),
            'document_type' => $this->faker->randomElement(['pdf', 'doc', 'docx', 'jpg', 'png']),
            'file_path' => 'storage/bookings/' . $this->faker->uuid() . '.pdf',
        ];
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'document_type' => 'pdf',
        ]);
    }
}