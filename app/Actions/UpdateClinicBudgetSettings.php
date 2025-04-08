<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Clinic;
use Brick\Money\Money;

final class UpdateClinicBudgetSettings
{
    /**
     * Handle updating or creating budget settings for a clinic.
     */
    public function handle(Clinic $clinic, array $data): void
    {
        // Money fields that need to be converted to minor units
        $moneyFields = [
            'weekly_cogs',
            'weekly_ga',
            'monthly_cogs',
            'monthly_ga',
            'avg_two_weeks_sales',
            'month_to_date_sales',
            'external_weekly_cogs',
            'external_monthly_cogs',
        ];

        // Process money fields
        foreach ($moneyFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = Money::of($data[$field], 'USD')->getMinorAmount()->toInt();
            }
        }

        // Direct assignment fields (no transformation needed)
        $directFields = [
            'type',
            'target_cogs_percent',
            'target_ga_percent',
        ];

        // Remove any direct fields that aren't set
        foreach ($directFields as $field) {
            if (! isset($data[$field])) {
                unset($data[$field]);
            }
        }

        $clinic->budgetSettings()->updateOrCreate([
            'clinic_id' => $clinic->id,
        ], $data);
    }
}
