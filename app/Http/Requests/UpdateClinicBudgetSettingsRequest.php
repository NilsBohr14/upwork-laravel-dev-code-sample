<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ClinicBudgetType;
use App\Models\Clinic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateClinicBudgetSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [Clinic::class, $this->route('clinic')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $staticRules = [
            'weekly_cogs' => ['decimal:0,2', 'min:0'],
            'weekly_ga' => ['decimal:0,2', 'min:0'],
            'monthly_cogs' => ['decimal:0,2', 'min:0'],
            'monthly_ga' => ['decimal:0,2', 'min:0'],
        ];

        $dynamicRules = [
            'target_cogs_percent' => ['decimal:0,2', 'min:0', 'max:1'],
            'target_ga_percent' => ['decimal:0,2', 'min:0', 'max:1'],
            'avg_two_weeks_sales' => ['decimal:0,2', 'min:0'],
            'month_to_date_sales' => ['decimal:0,2', 'min:0'],
        ];

        $externalDataRules = [
            'external_weekly_cogs' => ['decimal:0,2', 'min:0'],
            'external_monthly_cogs' => ['decimal:0,2', 'min:0'],
        ];

        return [
            ...[
                'type' => ['required', new Enum(ClinicBudgetType::class)],
                'include_external_data' => ['boolean'],
            ],
            ...$this->conditionalRules($staticRules, ClinicBudgetType::Static),
            ...$this->conditionalRules($dynamicRules, ClinicBudgetType::Dynamic),
            ...$this->conditionalRules($externalDataRules, null, 'include_external_data'),
        ];
    }

    /**
     * Apply conditional rules based on the budget type or external data inclusion.
     */
    private function conditionalRules(array $rules, ?ClinicBudgetType $budgetType = null, ?string $condition = null): array
    {
        $prefix = $budgetType ? "type,{$budgetType->value}" : $condition;

        return array_map(fn ($rule) => array_merge(["required_if:{$prefix},true"], $rule), $rules);
    }
}
