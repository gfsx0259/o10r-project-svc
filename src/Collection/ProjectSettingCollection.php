<?php

namespace App\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class ProjectSettingCollection extends ArrayCollection
{
    public function toKeyValue(): array
    {
        $result = [];
        foreach ($this as $setting) {
            $result[$setting->getCode()] = $setting->getValue();
        }
        return $result;
    }
}
