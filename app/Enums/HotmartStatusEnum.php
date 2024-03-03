<?php

namespace App\Enums;

enum HotmartStatusEnum: string
{
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
}
