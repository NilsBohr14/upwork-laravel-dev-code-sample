<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Clinic;

final class UpdateClinicSettings
{
    /**
     * Handle updating or creating settings for a clinic.
     */
    public function handle(Clinic $clinic, string $key, array $data): void
    {
        $clinic->settings()->updateOrCreate(
            ['clinic_id' => $clinic->id, 'key' => $key],
            ['value' => $data],
        );
    }
}
