<?php

namespace Database\Factories;

use App\Models\WorkflowStep;
use App\Models\Workflow;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowStepFactory extends Factory
{
    protected $model = WorkflowStep::class;

    /**
     * Generate satu langkah dalam rantai persetujuan workflow.
     * step_order menentukan urutan: 1 = pertama disetujui, dst.
     */
    public function definition(): array
    {
        return [
            'workflow_id'          => Workflow::inRandomOrder()->first()?->id ?? 1,
            'position_id'          => Position::inRandomOrder()->first()?->id ?? 1,
            // step_order: urutan persetujuan dalam workflow
            'step_order'           => $this->faker->numberBetween(1, 3),
            // requires_attachment: true = approver wajib upload dokumen sebelum bisa approve
            'requires_attachment'  => false,
        ];
    }

    /** State: langkah tertentu dalam workflow */
    public function step(int $workflowId, int $positionId, int $order, bool $requiresAttachment = false): static
    {
        return $this->state(fn(array $attr) => [
            'workflow_id'         => $workflowId,
            'position_id'         => $positionId,
            'step_order'          => $order,
            'requires_attachment' => $requiresAttachment,
        ]);
    }
}