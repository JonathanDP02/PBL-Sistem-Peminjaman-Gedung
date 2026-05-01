<?php

namespace Database\Factories;

use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkflowRequirement>
 */
class WorkflowRequirementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'workflow_id' => Workflow::factory(),
            'document_name' => $this->faker->randomElement([
                'Proposal Acara',
                'Surat Resmi dari Unit',
                'Persetujuan Dosen Pembimbing',
                'Format Rundown Acara',
                'Bukti Kesanggupan Peserta',
                'Surat Disposisi Wadir',
                'Surat Izin Peminjaman',
            ]),
            'is_mandatory' => $this->faker->boolean(70),
        ];
    }

    public function mandatory(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_mandatory' => true,
        ]);
    }

    public function optional(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_mandatory' => false,
        ]);
    }
}
