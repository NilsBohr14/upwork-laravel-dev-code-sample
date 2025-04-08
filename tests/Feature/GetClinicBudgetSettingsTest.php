<?php

declare(strict_types=1);

use App\Enums\AccountRole;
use App\Models\Clinic;
use App\Models\ClinicBudgetSettings;
use App\Models\User;
use Illuminate\Testing\TestResponse;

function getClinicBudgetSettings(string $clinicId): TestResponse
{
    return test()->getJson("/api/clinics/{$clinicId}/budget-settings");
}

beforeEach(function () {
    $this->clinicBudgetSettings = ClinicBudgetSettings::factory()->create();
});

describe('retrieve clinic budget settings', function () {

    describe('authorization', function () {

        it('prevents unauthorized users from accessing clinics budget settings', function () {

            getClinicBudgetSettings($this->clinicBudgetSettings->clinic->id)->assertUnauthorized();

        });

        it('alows authorized account users to access clinics budget settings', function (AccountRole $role) {

            $user = User::factory()->for($this->clinicBudgetSettings->clinic->account)->create(['role' => $role]);

            $this->actingAs($user);

            getClinicBudgetSettings($this->clinicBudgetSettings->clinic->id)->assertOk();

        })->with(AccountRole::cases());

    });

    describe('retrieve clinic budget settings with correct response structure', function () {

        it('retrieves the budget settings for the clinic with the correct response structure', function () {
            $user = User::factory()->for($this->clinicBudgetSettings->clinic->account)
                ->create(['role' => AccountRole::Administrator]);

            $this->actingAs($user);

            $response = getClinicBudgetSettings($this->clinicBudgetSettings->clinic->id);

            $response->assertOk()
                ->assertJson([
                    'type' => $this->clinicBudgetSettings->type->value,
                    'weeklyCogs' => (string) ($this->clinicBudgetSettings->weekly_cogs / 100),
                    'weeklyGa' => (string) ($this->clinicBudgetSettings->weekly_ga / 100),
                    'monthlyCogs' => (string) ($this->clinicBudgetSettings->monthly_cogs / 100),
                    'monthlyGa' => (string) ($this->clinicBudgetSettings->monthly_ga / 100),
                    'targetCogsPercent' => (string) ($this->clinicBudgetSettings->target_cogs_percent),
                    'targetGaPercent' => (string) ($this->clinicBudgetSettings->target_ga_percent),
                    'avgTwoWeeksSales' => (string) ($this->clinicBudgetSettings->avg_two_weeks_sales / 100),
                    'monthToDateSales' => (string) ($this->clinicBudgetSettings->month_to_date_sales / 100),
                    'includeExternalData' => $this->clinicBudgetSettings->include_external_data,
                    'externalWeeklyCogs' => (string) ($this->clinicBudgetSettings->external_weekly_cogs / 100),
                    'externalMonthlyCogs' => (string) ($this->clinicBudgetSettings->external_monthly_cogs / 100),
                    'updatedAt' => $this->clinicBudgetSettings->updated_at->toIso8601String(),
                ]);

        });

        it('returns an empty object when budget settings do not exist', function () {
            $clinic = Clinic::factory()->create();

            $user = User::factory()->for($clinic->account)->create(['role' => AccountRole::Administrator]);

            $this->actingAs($user);

            $response = getClinicBudgetSettings($clinic->id);

            $response->assertOk()
                ->assertJson([
                    'type' => null,
                    'weeklyCogs' => '0.00',
                    'weeklyGa' => '0.00',
                    'monthlyCogs' => '0.00',
                    'monthlyGa' => '0.00',
                    'targetCogsPercent' => '0.00',
                    'targetGaPercent' => '0.00',
                    'avgTwoWeeksSales' => '0.00',
                    'monthToDateSales' => '0.00',
                    'includeExternalData' => false,
                    'externalWeeklyCogs' => '0.00',
                    'externalMonthlyCogs' => '0.00',
                    'updatedAt' => null,
                ]);
        });

    });
});
