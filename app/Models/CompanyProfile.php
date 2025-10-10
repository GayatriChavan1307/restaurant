<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'tagline',
        'description',
        'about_us',
        'mission',
        'vision',
        'values',
        'logo',
        'favicon',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'established_year',
        'employee_count',
        'annual_turnover',
        'export_countries',
        'certifications',
        'social_links',
        'business_hours',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'export_countries' => 'array',
        'certifications' => 'array',
        'social_links' => 'array',
        'business_hours' => 'array',
        'established_year' => 'integer',
        'employee_count' => 'integer',
        'annual_turnover' => 'decimal:2'
    ];

    public function getFullAddressAttribute()
    {
        return trim("{$this->address}, {$this->city}, {$this->state}, {$this->country} {$this->postal_code}");
    }

    public static function getProfile()
    {
        return static::first() ?? new static();
    }
}