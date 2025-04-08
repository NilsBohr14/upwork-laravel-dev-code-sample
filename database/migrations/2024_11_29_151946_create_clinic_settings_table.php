<?php

declare(strict_types=1);

use App\Enums\ClinicSettingsType;
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
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Clinic::class)->constrained()->cascadeOnDelete();
            $table->enum('key', ClinicSettingsType::values());
            $table->json('value');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['clinic_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_settings');
    }
};
