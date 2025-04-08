<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\UpdateClinicBudgetSettings;
use App\Http\Requests\UpdateClinicBudgetSettingsRequest;
use App\Http\Resources\ClinicBudgetSettings;
use App\Models\Clinic;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

final class ClinicBudgetSettingsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get the given clinics budget settings.
     */
    public function show(Clinic $clinic): JsonResponse
    {
        $this->authorize('view', $clinic);

        $settings = $clinic->budgetSettings()->firstOrNew();

        return ClinicBudgetSettings::make($settings)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateClinicBudgetSettingsRequest $request,
        UpdateClinicBudgetSettings $action,
        Clinic $clinic
    ): JsonResponse {

        $action->handle($clinic, $request->validated());

        $clinic->load('budgetSettings');

        return ClinicBudgetSettings::make($clinic->budgetSettings)->response();
    }
}
