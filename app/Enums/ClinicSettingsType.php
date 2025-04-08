<?php

declare(strict_types=1);

namespace App\Enums;

enum ClinicSettingsType: string
{
    use EnumMethods;

    case Budget = 'BUDGET';
    case ControlledDrugs = 'CONTROLLED_DRUGS';
}
