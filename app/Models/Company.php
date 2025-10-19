<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'tax_number',
        'business_number',
        'description',
        'owner_name',
        'owner_phone',
        'owner_email',
    ];

    // Relasi dengan user (jika diperlukan)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Method untuk mendapatkan logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/'.$this->logo);
        }

        return asset('images/default-company-logo.png');
    }
}
