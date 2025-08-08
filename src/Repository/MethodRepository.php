<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Catalog\Method;

use App\Exception\NotFoundException;
use Cycle\ORM\Select\Repository;

final class MethodRepository extends Repository
{
    /**
     * @param string $code
     * @return Method
     * @throws NotFoundException
     */
    public function getByCode(string $code): Method
    {
        if (!$method = $this->findOne(['code' => $code])) {
            throw new NotFoundException();
        }

        return $method;
    }
}
