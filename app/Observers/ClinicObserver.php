<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\VendorConnectionStatus;
use App\Models\Clinic;
use App\Models\Vendor;

final class ClinicObserver
{
    /**
     * Handle the Clinic "created" event.
     */
    public function created(Clinic $clinic): void
    {
        $vendors = Vendor::all();

        foreach ($vendors as $vendor) {
            $vendor->clinics()->attach($clinic, ['status' => VendorConnectionStatus::NotConnected]);
        }
    }
}
