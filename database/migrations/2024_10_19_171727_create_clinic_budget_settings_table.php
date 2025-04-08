<?php

declare(strict_types=1);

use App\Enums\ClinicBudgetType;
use App\Models\Clinic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_budget_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Clinic::class)->unique()->cascadeOnDelete();
            $table->enum('type', ClinicBudgetType::values())->default(ClinicBudgetType::Static);
            $table->unsignedInteger('weekly_cogs')->default(0);
            $table->unsignedInteger('weekly_ga')->default(0);
            $table->unsignedInteger('monthly_cogs')->default(0);
            $table->unsignedInteger('monthly_ga')->default(0);
            $table->decimal('target_cogs_percent', 3, 2)->unsigned()->default(0);
            $table->decimal('target_ga_percent', 3, 2)->unsigned()->default(0);
            $table->unsignedInteger('avg_two_weeks_sales')->default(0);
            $table->unsignedInteger('month_to_date_sales')->default(0);
            $table->boolean('include_external_data')->default(false);
            $table->unsignedInteger('external_weekly_cogs')->default(0);
            $table->unsignedInteger('external_monthly_cogs')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_budget_settings');
    }
};
