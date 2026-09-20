<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'address_recipient',
        'address_phone',
        'address',
        'address_city',
        'address_province',
        'address_district',
        'address_subdistrict',
        'address_village',
        'address_postal_code',
        'password',

    ];
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isMember()
    {
        return $this->role->name === 'member';
    }

    public function isCashier()
    {
        return $this->role->name === 'cashier';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function stockhistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function carts()
    {
       return $this->hasMany(Cart::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
