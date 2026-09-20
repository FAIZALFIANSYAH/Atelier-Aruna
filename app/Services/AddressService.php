<?php
namespace App\Services;
use App\Models\User;
class AddressService
{
    public function isComplete(User $user): bool
    {
        return collect(['address', 'address_recipient', 'address_phone', 'address_city', 'address_postal_code', 'address_province', 'address_district', 'address_subdistrict', 'address_village'])->every(fn ($field) => filled($user->{$field}));
    }
}
