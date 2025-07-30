<?php

namespace App\Collection;

use App\Entity\ProjectMethod;
use Doctrine\Common\Collections\ArrayCollection;

class ProjectMethodCollection extends ArrayCollection
{
    public function getIds(): array
    {
        return array_map(fn (ProjectMethod $projectMethod) => $projectMethod->getMethodId(), $this->toArray());
    }
}
