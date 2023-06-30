<?php

namespace App\config\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';

    case WFC = 'waiting_for_capture';

    case Succeeded = 'succeeded';

    case Canceled = 'canceled';

    case Refunded = 'refunded';
}
