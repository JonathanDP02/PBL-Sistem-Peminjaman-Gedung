<?php

namespace Database\Factories;

use App\Models\Workflow;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowFactory extends Factory
{
    protected $model = Workflow::class;

    /**
     * Generate workflow (alur persetujuan) untuk sebuah unit.
     */
    public function definition(): array
    {
        return [
            'unit_id'     => Unit::inRandomOrder()->first()?->id ?? 1,
            'name'        => 'Peminjaman ' . $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
        ];
    }

    /** State: workflow milik unit tertentu */
    public function forUnit(int $unitId, string $name): static
    {
        return $this->state(fn(array $attr) => [
            'unit_id'     => $unitId,
            'name'        => $name,
            'description' => 'Alur persetujuan untuk ' . $name,
        ]);
    }
}