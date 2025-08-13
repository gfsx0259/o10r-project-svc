<?php

namespace App\Module\Dummy\Collection;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArrayCollectionCast implements NormalizerInterface, DenormalizerInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ArrayCollection;
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        return $data->data;
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === ArrayCollection::class;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = []): ArrayCollection
    {
        return new ArrayCollection($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['*' => false];
    }
}
