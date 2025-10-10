<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'contact_person',
        'description',
        'certifications',
        'is_active',
        'rating'
    ];

    protected $casts = [
        'certifications' => 'array',
        'is_active' => 'boolean',
        'rating' => 'decimal:1'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullAddressAttribute()
    {
        return trim("{$this->address}, {$this->city}, {$this->state}, {$this->country} {$this->postal_code}");
    }
}