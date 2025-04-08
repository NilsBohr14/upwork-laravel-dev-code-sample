<?php

declare(strict_types=1);

namespace App\Enums;

enum ClinicBudgetType: string
{
    use EnumMethods;

    case Dynamic = 'DYNAMIC';
    case Static = 'STATIC';
}
