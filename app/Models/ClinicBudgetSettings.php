<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClinicBudgetType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ClinicBudgetSettings extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'weekly_cogs',
        'weekly_ga',
        'monthly_cogs',
        'monthly_ga',
        'target_cogs_percent',
        'target_ga_percent',
        'avg_two_weeks_sales',
        'month_to_date_sales',
        'include_external_data',
        'external_weekly_cogs',
        'external_monthly_cogs',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, mixed>
     */
    protected $casts = [
        'type' => ClinicBudgetType::class,
        'include_external_data' => 'boolean',
        'target_cogs_percent' => 'float',
        'target_ga_percent' => 'float',
    ];

    /**
     * The attributes that should be set to default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'weekly_cogs' => 0,
        'weekly_ga' => 0,
        'monthly_cogs' => 0,
        'monthly_ga' => 0,
        'target_cogs_percent' => 0,
        'target_ga_percent' => 0,
        'avg_two_weeks_sales' => 0,
        'month_to_date_sales' => 0,
        'include_external_data' => false,
        'external_weekly_cogs' => 0,
        'external_monthly_cogs' => 0,
    ];

    /**
     * The clinic that belongs to the budget.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
