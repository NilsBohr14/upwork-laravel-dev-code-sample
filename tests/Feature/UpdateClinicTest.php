<?php

declare(strict_types=1);

use App\Enums\AccountRole;
use App\Enums\AddressType;
use App\Models\Account;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Testing\TestResponse;

function updateClinic(Clinic $clinic, array $data): TestResponse
{
    return test()->patchJson("/api/clinics/{$clinic->id}", $data);
}

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->member = User::factory()->create();

    $this->account = Account::factory()->create();
    $this->owner->update(['account_id' => $this->account->id, 'role' => AccountRole::Administrator]);
    $this->member->update(['account_id' => $this->account->id, 'role' => AccountRole::Purchaser]);

    $this->clinic = Clinic::factory()->for($this->account)->create([
        'name' => 'Animal Talk Clinic',
        'phone_number' => '1-800-555-1234',
    ]);
    $this->clinic->billingAddress()->create([
        'type' => AddressType::Billing,
        'street' => '742 Evergreen Terrace',
        'city' => 'Anytown',
        'state' => 'CA',
        'postal_code' => '12345',
    ]);
    $this->clinic->shippingAddress()->create([
        'type' => AddressType::Shipping,
        'street' => '742 Evergreen Terrace',
        'city' => 'Anytown',
        'state' => 'CA',
        'postal_code' => '12345',
    ]);
});

describe('update clinic', function () {
    it('allows clinic owners to update the clinic', function () {
        $this->actingAs($this->owner);

        updateClinic($this->clinic, [
            'name' => 'Purrfect Pals Pet Clinic',
            'phoneNumber' => '1-888-MEOW-WOOF',
            'address' => [
                'street' => '123 Whisker Lane',
                'city' => 'Pawsville',
                'state' => 'NY',
                'postalCode' => '54321',
            ],
        ])
            ->assertOk()
            ->assertJson([
                'name' => 'Purrfect Pals Pet Clinic',
                'phoneNumber' => '1-888-MEOW-WOOF',
                'billingAddress' => [
                    'street' => '123 Whisker Lane',
                    'city' => 'Pawsville',
                    'state' => 'NY',
                    'postalCode' => '54321',
                ],
                'shippingAddress' => [
                    'street' => '123 Whisker Lane',
                    'city' => 'Pawsville',
                    'state' => 'NY',
                    'postalCode' => '54321',
                ],
            ]);

        $this->clinic->refresh();

        expect($this->clinic->name)->toBe('Purrfect Pals Pet Clinic');
        expect($this->clinic->phone_number)->toBe('1-888-MEOW-WOOF');
        expect($this->clinic->billingAddress)
            ->street->toBe('123 Whisker Lane')
            ->city->toBe('Pawsville')
            ->state->toBe('NY')
            ->postal_code->toBe('54321');
        expect($this->clinic->shippingAddress)
            ->street->toBe('123 Whisker Lane')
            ->city->toBe('Pawsville')
            ->state->toBe('NY')
            ->postal_code->toBe('54321');
    });

    it('does not allow non-owners to update the clinic', function () {
        $this->actingAs($this->member);

        updateClinic($this->clinic, [])->assertForbidden();

        $this->clinic->refresh();

        expect($this->clinic->name)->toBe('Animal Talk Clinic');
        expect($this->clinic->phone_number)->toBe('1-800-555-1234');
        expect($this->clinic->billingAddress)
            ->street->toBe('742 Evergreen Terrace')
            ->city->toBe('Anytown')
            ->state->toBe('CA')
            ->postal_code->toBe('12345');
        expect($this->clinic->shippingAddress)
            ->street->toBe('742 Evergreen Terrace')
            ->city->toBe('Anytown')
            ->state->toBe('CA')
            ->postal_code->toBe('12345');
    });

    it('does not allow updating to a duplicate name', function () {
        Clinic::factory()->create(['name' => 'Existing Clinic']);

        $this->actingAs($this->owner);

        updateClinic($this->clinic, ['name' => 'Existing Clinic'])->assertUnprocessable();
    })->todo();

    it('does not clear the address when updating the clinic', function () {
        $this->actingAs($this->owner);

        updateClinic($this->clinic, ['address' => null])->assertOk();
    });

    it('does not clear empty fields when updating the clinic', function () {
        $this->actingAs($this->owner);

        updateClinic($this->clinic, [])->assertOk();

        $this->clinic->refresh();

        expect($this->clinic->name)->toBe('Animal Talk Clinic');
        expect($this->clinic->phone_number)->toBe('1-800-555-1234');
        expect($this->clinic->billingAddress)
            ->street->toBe('742 Evergreen Terrace')
            ->city->toBe('Anytown')
            ->state->toBe('CA')
            ->postal_code->toBe('12345');
        expect($this->clinic->shippingAddress)
            ->street->toBe('742 Evergreen Terrace')
            ->city->toBe('Anytown')
            ->state->toBe('CA')
            ->postal_code->toBe('12345');
    });
});
