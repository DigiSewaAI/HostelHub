<?php

namespace App\Enums;

enum MessageCategoryEnum: string
{
    case BUSINESS_INQUIRY = 'business_inquiry';
    case PARTNERSHIP = 'partnership';
    case HOSTEL_SALE = 'hostel_sale';
    case EMERGENCY = 'emergency';
    case GENERAL = 'general';
}
