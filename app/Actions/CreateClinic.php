<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\AccountRole;
use App\Enums\AddressType;
use App\Models\Account;
use App\Models\Clinic;
use App\Models\User;
use App\Notifications\ManagerAddedToClinic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class CreateClinic
{
    /**
     * Handle the creation of clinics for the given account.
     */
    public function handle(Account $account, array $data): Collection
    {
        return collect($data)->map(fn ($item) => $this->createSingleClinic($account, $item))->values();
    }

    /**
     * Create a single clinic with its relationships.
     */
    private function createSingleClinic(Account $account, array $data): Clinic
    {
        [$clinic, $manager] = DB::transaction(function () use ($account, $data) {
            $clinic = $account->clinics()->create([
                ...$data,
                'is_billing_same_as_shipping_address' => true,
            ]);

            if (isset($data['address'])) {
                $clinic->billingAddress()->create(['type' => AddressType::Billing, ...$data['address']]);
                $clinic->shippingAddress()->create(['type' => AddressType::Shipping, ...$data['address']]);
            }

            if (isset($data['manager'])) {
                $manager = $this->createManagerIfNeeded($clinic, $data['manager']);
            }

            return [$clinic->load(['billingAddress', 'shippingAddress', 'vendors']), $manager ?? null];
        });

        if ($manager) {
            Password::sendResetLink(['email' => $manager->email], function (User $user, string $token) use ($clinic) {
                $user->notify(new ManagerAddedToClinic($clinic->name, $token));
            });
        }

        return $clinic;
    }

    /**
     * Create a manager user.
     */
    private function createManagerIfNeeded(Clinic $clinic, array $data): User
    {
        return tap(
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt(Str::random(16)),
                    'role' => AccountRole::Manager,
                    'account_id' => $clinic->account_id,
                ]
            ),
            fn (User $user) => $clinic->users()->attach($user),
        );
    }
}
