<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ClinicControlledDrugsOrderFrequency;
use App\Enums\ClinicSettingsType;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClinicSetting>
 */
final class ClinicSettingFactory extends Factory
{
    /**
     * Define the clinic setting's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'key' => $this->faker->randomElement(ClinicSettingsType::class),
            'value' => [],
        ];
    }

    /**
     * Indicate that the clinic setting is for controlled drugs.
     */
    public function ofControlledDrugs(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                'key' => ClinicSettingsType::ControlledDrugs->value,
                'value' => $this->controlledDrugsSettingsValue(),
            ];
        });
    }

    /**
     * Get the value for the controlled drugs settings.
     *
     * @return array<string, mixed>
     */
    private function controlledDrugsSettingsValue(): array
    {
        return [
            'controlledSubstancePurchases' => [
                'controlledSubstancePct' => $this->faker->randomFloat(1, 1, 100),
                'nonControlledPrescriptionsPct' => $this->faker->randomFloat(1, 1, 100),
                'nonPrescriptionsPct' => $this->faker->randomFloat(1, 1, 100),
            ],
            'orderingPattern' => $this->faker->randomElement(ClinicControlledDrugsOrderFrequency::class),
            'otherSuppliers' => $this->faker->sentence(),
            'topControlledSubstances' => array_fill(0, 4, [
                'productName' => $this->faker->word(),
                'quantity' => $this->faker->randomNumber(),
            ]),
            'intendedControlledSubstances' => array_fill(0, 4, [
                'productName' => $this->faker->word(),
                'strength' => $this->faker->numerify('###mg'),
                'quantity' => $this->faker->randomNumber(),
                'frequency' => $this->faker->randomElement(ClinicControlledDrugsOrderFrequency::class),
            ]),
            'isRegisteredWithCsos' => $this->faker->boolean(),
            'lastDeaInspectionDate' => $this->faker->date(),
            'administerMedicationsOnSite' => $this->faker->boolean(),
            'takeBackControlledSubstances' => $this->faker->boolean(),
            'reasonTakingBackControlledSubstances' => $this->faker->sentence(),
            'areLicensesCurrent' => $this->faker->boolean(),
            'registrantIssues' => array_fill(0, 4, $this->faker->sentence()),
            'inventoryManager' => $this->faker->name(),
            'everCutOffFromPurchasing' => $this->faker->boolean(),
            'maintainControlledSubstancesLog' => $this->faker->boolean(),
            'reasonNotMaintainingLog' => $this->faker->sentence(),
            'hasSecurityPolicies' => $this->faker->boolean(),
            'reasonNoSecurityPolicies' => $this->faker->sentence(),
            'areEmployeesTrained' => $this->faker->boolean(),
            'reasonEmployeesNotTrained' => $this->faker->sentence(),
            'hasOtherDeaBusinesses' => $this->faker->boolean(),
            'reasonNotDeaPermit' => $this->faker->sentence(),
        ];
    }
}
