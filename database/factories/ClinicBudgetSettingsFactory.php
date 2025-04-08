<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ClinicBudgetType;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClinicBudgetSettings>
 */
final class ClinicBudgetSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'type' => ClinicBudgetType::Static,
            'weekly_cogs' => $this->faker->numberBetween(0, 100000),
            'weekly_ga' => $this->faker->numberBetween(0, 100000),
            'monthly_cogs' => $this->faker->numberBetween(0, 100000),
            'monthly_ga' => $this->faker->numberBetween(0, 100000),
            'target_cogs_percent' => $this->faker->randomFloat(2, 1, 100),
            'target_ga_percent' => $this->faker->randomFloat(2, 1, 100),
            'avg_two_weeks_sales' => $this->faker->numberBetween(0, 100000),
            'month_to_date_sales' => $this->faker->numberBetween(0, 100000),
            'include_external_data' => $this->faker->boolean(),
            'external_weekly_cogs' => $this->faker->numberBetween(0, 100000),
            'external_monthly_cogs' => $this->faker->numberBetween(0, 100000),
        ];
    }
}
