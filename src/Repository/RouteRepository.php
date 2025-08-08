<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gateway\Route;
use Cycle\ORM\Select\Repository;

final class RouteRepository extends Repository
{
    public function getByMethod(int $methodId): Route
    {
        return $this->findOne(['method_id' => $methodId]);
    }
}
