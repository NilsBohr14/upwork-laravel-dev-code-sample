<?php

declare(strict_types=1);

use App\Enums\AccountRole;
use App\Enums\ClinicBudgetType;
use App\Models\Clinic;
use App\Models\ClinicBudgetSettings;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

function updateClinicBudgetSettings(string $clinicId, array $data): TestResponse
{
    return test()->putJson("/api/clinics/{$clinicId}/budget-settings", $data);
}

function getStaticBudgetData(array $overrides = []): array
{
    return array_merge([
        'type' => ClinicBudgetType::Static->value,
        'weeklyCogs' => '100.00',
        'weeklyGa' => '100.00',
        'monthlyCogs' => '400.00',
        'monthlyGa' => '400.00',
    ], $overrides);
}

function getDynamicBudgetData(array $overrides = []): array
{
    return array_merge([
        'type' => ClinicBudgetType::Dynamic->value,
        'targetCogsPercent' => 0.5,
        'targetGaPercent' => 0.5,
        'avgTwoWeeksSales' => '100.00',
        'monthToDateSales' => '200.00',
    ], $overrides);
}

beforeEach(function () {
    $this->clinic = Clinic::factory()->create();
    $this->owner = User::factory()->create();
    $this->member = User::factory()->create();

    $this->owner->update(['account_id' => $this->clinic->account->id, 'role' => AccountRole::Administrator]);
    $this->member->update(['account_id' => $this->clinic->account->id, 'role' => AccountRole::Purchaser]);
});

describe('update clinic budget settings', function () {
    // describe('static budget settings', function () {
    //     it('sets the static budget settings for a clinic', function () {
    //         $this->actingAs($this->owner);

    //         $data = getStaticBudgetData([
    //             'weeklyCogs' => '125.00', // $125
    //             'weeklyGa' => '87.55', // $87.55
    //             'monthlyCogs' => '520.00', // $520
    //             'monthlyGa' => '360.00', // $360
    //         ]);

    //         updateClinicBudgetSettings($this->clinic->id, $data)
    //             ->assertOk()
    //             ->assertJson([
    //                 'type' => ClinicBudgetType::Static->value,
    //                 'weeklyCogs' => '125.00', // $125
    //                 'weeklyGa' => '87.55', // $87.55
    //                 'monthlyCogs' => '520.00', // $520
    //                 'monthlyGa' => '360.00', // $360
    //             ]);
    //     });

    //     it('updates existing static budget settings', function () {
    //         ClinicBudgetSettings::factory()->for($this->clinic)->create([
    //             'type' => ClinicBudgetType::Static,
    //             'weekly_cogs' => '90.00',
    //             'weekly_ga' => '75.00',
    //         ]);

    //         $this->actingAs($this->owner);

    //         $data = getStaticBudgetData([
    //             'weeklyCogs' => '110.00', // $110
    //             'weeklyGa' => '80.00', // $80
    //         ]);

    //         updateClinicBudgetSettings($this->clinic->id, $data)
    //             ->assertOk()
    //             ->assertJson([
    //                 'type' => ClinicBudgetType::Static->value,
    //                 'weeklyCogs' => '110.00', // $110
    //                 'weeklyGa' => '80.00', // $80
    //             ]);
    //     });
    // });

    describe('dynamic budget settings', function () {
        // it('sets the dynamic budget settings for a clinic', function () {
        //     $this->actingAs($this->owner);

        //     $data = getDynamicBudgetData([
        //         'targetCogsPercent' => 0.55,
        //         'targetGaPercent' => 0.45,
        //         'avgTwoWeeksSales' => '150.00', // $150
        //         'monthToDateSales' => '350.00', // $350
        //     ]);

        //     updateClinicBudgetSettings($this->clinic->id, $data)
        //         ->assertOk()
        //         ->assertJson([
        //             'type' => ClinicBudgetType::Dynamic->value,
        //             'targetCogsPercent' => 0.55,
        //             'targetGaPercent' => 0.45,
        //             'avgTwoWeeksSales' => '150.00', // $150
        //             'monthToDateSales' => '350.00', // $350
        //         ]);
        // });

        it('updates the budget settings from static to dynamic', function () {
            ClinicBudgetSettings::factory()->for($this->clinic)->create([
                'type' => ClinicBudgetType::Static,
            ]);

            $this->actingAs($this->owner);

            $data = getDynamicBudgetData([
                'targetCogsPercent' => 0.6,
                'targetGaPercent' => 0.4,
                'avgTwoWeeksSales' => '180.00', // $180
                'monthToDateSales' => '420.00', // $420
            ]);

            updateClinicBudgetSettings($this->clinic->id, $data)
                ->assertOk()
                ->assertJson([
                    'type' => ClinicBudgetType::Dynamic->value,
                    'targetCogsPercent' => 0.6,
                    'targetGaPercent' => 0.4,
                    'avgTwoWeeksSales' => '180.00', // $180
                    'monthToDateSales' => '420.00', // $420
                ]);
        });
    });

    // describe('authorization', function () {
    //     it('prevents unauthorized users from updating budget settings', function () {
    //         $this->actingAs($this->member);

    //         $data = getStaticBudgetData();

    //         updateClinicBudgetSettings($this->clinic->id, $data)
    //             ->assertStatus(Response::HTTP_FORBIDDEN);
    //     });
    // });
});
