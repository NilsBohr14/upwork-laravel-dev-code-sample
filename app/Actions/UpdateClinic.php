<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\AddressType;
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;

final class UpdateClinic
{
    /**
     * Handle updating an clinic.
     */
    public function handle(Clinic $clinic, array $data): Clinic
    {
        return DB::transaction(function () use ($clinic, $data) {
            $this->updateClinicDetails($clinic, $data);
            $this->updateClinicAddress($clinic, $data['address'] ?? []);

            return $clinic->fresh();
        });
    }

    /**
     * Update the clinic's details.
     */
    private function updateClinicDetails(Clinic $clinic, array $data): void
    {
        $clinic->update(array_diff_key($data, ['address' => null]));
    }

    /**
     * Update or create the clinic's address.
     */
    private function updateClinicAddress(Clinic $clinic, array $data): void
    {
        if (! empty($data)) {
            $clinic->billingAddress()->updateOrCreate(['type' => AddressType::Billing], $data);
            $clinic->shippingAddress()->updateOrCreate(['type' => AddressType::Shipping], $data);
        }
    }
}
