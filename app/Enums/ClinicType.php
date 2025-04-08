<?php

declare(strict_types=1);

namespace App\Enums;

enum ClinicType: string
{
    use EnumMethods;

    case Emergency = 'EMERGENCY';
    case Mobile = 'MOBILE';
    case Other = 'OTHER';
    case Research = 'RESEARCH';
    case Shelter = 'SHELTER';
    case Traditional = 'TRADITIONAL';
    case University = 'UNIVERSITY';
}
