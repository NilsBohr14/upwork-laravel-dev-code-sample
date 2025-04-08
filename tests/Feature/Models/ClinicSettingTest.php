<?php

declare(strict_types=1);

use App\Models\Clinic;
use App\Models\ClinicSetting;

test('to array', function () {
    $setting = ClinicSetting::factory()->create()->fresh();
    expect(array_keys($setting->toArray()))
        ->toEqual([
            'id',
            'clinic_id',
            'key',
            'value',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

it('belongs to a clinic', function () {
    $setting = ClinicSetting::factory()->create();

    expect($setting->clinic)->toBeInstanceOf(Clinic::class);
});
