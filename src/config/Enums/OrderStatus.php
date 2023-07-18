<?php

namespace App\config\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';

    case WFC = 'waiting_for_capture';

    case Succeeded = 'succeeded';

    case Canceled = 'canceled';

    case Refunded = 'refunded';

    public static function getCurrentStatuses(): array
    {
        return [OrderStatus::Pending->value, OrderStatus::WFC->value];
    }

    public static function getStatus(string $value): OrderStatus
    {
        return self::from($value);
    }
}
