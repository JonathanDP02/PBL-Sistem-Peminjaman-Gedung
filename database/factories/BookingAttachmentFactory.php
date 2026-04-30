<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\User;
use App\Models\WorkflowRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingAttachment>
 */
class BookingAttachmentFactory extends Factory
{
    protected $model = BookingAttachment::class;

    public function definition(): array
    {
        $filename = $this->faker->slug().'.pdf';

        return [
            'booking_id' => Booking::factory(),
            'requirement_id' => WorkflowRequirement::factory(),
            'uploader_id' => User::factory(),
            'document_type' => 'pdf',
            'file_path' => 'attachments/'.$filename,
        ];
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'document_type' => 'pdf',
            'file_path' => 'attachments/'.$this->faker->slug().'.pdf',
        ]);
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'document_type' => 'image',
            'file_path' => 'attachments/'.$this->faker->slug().'.jpg',
        ]);
    }

    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'document_type' => 'document',
            'file_path' => 'attachments/'.$this->faker->slug().'.docx',
        ]);
    }
}
