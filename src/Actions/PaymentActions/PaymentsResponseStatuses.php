<?php

namespace App\Actions\PaymentActions;

class PaymentsResponseStatuses
{
    public static function isSuccess(string $responseStatus): bool
    {
        return $responseStatus === 'succeeded';
    }
}