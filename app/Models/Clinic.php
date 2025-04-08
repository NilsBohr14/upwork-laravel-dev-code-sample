<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AccountRole;
use App\Enums\AddressType;
use App\Enums\ClinicSettingsType;
use App\Observers\ClinicObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([ClinicObserver::class])]
final class Clinic extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'business_tax_id',
        'phone_number',
        'is_billing_same_as_shipping_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_billing_same_as_shipping_address' => 'boolean',
    ];

    /**
     * Get the account that owns the clinic.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * The billing address that belongs to the clinic.
     */
    public function billingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', AddressType::Billing);
    }

    /**
     * The shipping address that belongs to the clinic.
     */
    public function shippingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', AddressType::Shipping);
    }

    /**
     * Get the budget settings that belong to the clinic.
     */
    public function budgetSettings(): HasOne
    {
        return $this->hasOne(ClinicBudgetSettings::class);
    }

    /**
     * Get the settings that belong to the clinic.
     */
    public function settings(): HasMany
    {
        return $this->hasMany(ClinicSetting::class);
    }

    /**
     * Get the controlled drugs settings that belong to the clinic.
     */
    public function controlledDrugsSettings(): HasOne
    {
        return $this->hasOne(ClinicSetting::class)->where('key', ClinicSettingsType::ControlledDrugs->value);
    }

    /**
     * Get the users that belong to the clinic.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the vendors that belong to the clinic.
     */
    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)
            ->where('is_enabled', true)
            ->using(ClinicVendor::class)
            ->withPivot('customer_number', 'credentials', 'status', 'error_message');
    }

    /**
     * Get the product syncs that belong to the clinic.
     */
    public function productsSyncs(): HasMany
    {
        return $this->hasMany(ProductSync::class);
    }

    /**
     * Get the cart that belongs to the clinic.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the orders that belong to the clinic.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the invoices that belong to the clinic.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the products that belong to the clinic.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('price', 'favorited_at');
    }

    /**
     * Get the favorite products that belong to the clinic.
     */
    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('favorited_at')
            ->wherePivot('favorited_at', '!=', null);
    }

    /**
     * Get the smart cart template that belongs to the clinic.
     */
    public function smartCartTemplate(): HasOne
    {
        return $this->hasOne(SmartCartTemplate::class);
    }

    /**
     * Get the managers of the clinic.
     */
    protected function managers(): Attribute
    {
        return new Attribute(
            get: fn () => $this->users->where('role', AccountRole::Manager),
        );
    }
}
