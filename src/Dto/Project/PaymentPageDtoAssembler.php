<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\Project\Project;

final readonly class PaymentPageDtoAssembler
{
    public function assemble(Project $project): PaymentPageDto
    {
        return new PaymentPageDto(
            new PaymentPageProjectDto($project->getId(), $project->getHash()),
            $project->getMethods()->getIds(),
            $project->getSettings()->toKeyValue(),
        );
    }
}
