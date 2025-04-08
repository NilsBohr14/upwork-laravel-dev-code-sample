<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ClinicBudgetSettings extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'weekly_cogs' => Money::ofMinor($this->weekly_cogs, 'USD')->getAmount(),
            'weekly_ga' => Money::ofMinor($this->weekly_ga, 'USD')->getAmount(),
            'monthly_cogs' => Money::ofMinor($this->monthly_cogs, 'USD')->getAmount(),
            'monthly_ga' => Money::ofMinor($this->monthly_ga, 'USD')->getAmount(),
            'target_cogs_percent' => $this->target_cogs_percent,
            'target_ga_percent' => $this->target_ga_percent,
            'avg_two_weeks_sales' => Money::ofMinor($this->avg_two_weeks_sales, 'USD')->getAmount(),
            'month_to_date_sales' => Money::ofMinor($this->month_to_date_sales, 'USD')->getAmount(),
            'include_external_data' => $this->include_external_data,
            'external_weekly_cogs' => Money::ofMinor($this->external_weekly_cogs, 'USD')->getAmount(),
            'external_monthly_cogs' => Money::ofMinor($this->external_monthly_cogs, 'USD')->getAmount(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
