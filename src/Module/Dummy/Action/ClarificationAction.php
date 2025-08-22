<?php

declare(strict_types=1);

namespace App\Module\Dummy\Action;

use App\Module\Dummy\Collection\ArrayCollection;

/**
 * In Clara Flow we don't have a parameter that can be provided and received back.
 * Use different hashes by field keys. But use payment id to restore state.
 */
readonly class ClarificationAction implements ActionInterface
{
    public function resolveAcceptedKey(ArrayCollection $callback): ?string
    {
        if ($callback->get('clarification_fields')) {
            return $this->generateHash(
                $callback->get('general.payment_id'),
                array_keys($callback->get('clarification_fields'))
            );
        }

        return null;
    }

    public function resolveCompletedKey(ArrayCollection $completePayload): ?string
    {
        return $this->generateHash(
            $completePayload->get('general.payment_id'),
            array_keys($completePayload->get('clarification'))
        );
    }

    private function generateHash(string $paymentId, array $fieldKeys): string
    {
        return md5($paymentId . implode($fieldKeys));
    }
}
