<?php

namespace App\Enums;

enum PurchaseStatusEnum: string
{
    case APPROVED = 'approved';

    case EXPIRED = 'expired';

    case CANCELLED = 'cancelled';
}
