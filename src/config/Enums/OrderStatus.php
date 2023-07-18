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
        return match ($value) {
            OrderStatus::Pending->value => OrderStatus::Pending,
            OrderStatus::WFC->value => OrderStatus::WFC,
            OrderStatus::Succeeded->value => OrderStatus::Succeeded,
            OrderStatus::Canceled->value => OrderStatus::Canceled,
            OrderStatus::Refunded->value => OrderStatus::Refunded,
        };
    }
}
