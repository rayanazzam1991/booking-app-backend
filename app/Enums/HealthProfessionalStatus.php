<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum HealthProfessionalStatus: string
{
    use EnumValues;
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';
}
