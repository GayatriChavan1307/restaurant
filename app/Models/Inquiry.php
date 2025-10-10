<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_QUOTED = 'quoted';
    const STATUS_CONVERTED = 'converted';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'inquiry_number',
        'product_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'company_name',
        'country',
        'quantity',
        'unit',
        'message',
        'status',
        'notes',
        'quoted_price',
        'quoted_currency',
        'quoted_at',
        'follow_up_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quoted_price' => 'decimal:2',
        'quoted_at' => 'datetime',
        'follow_up_date' => 'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_PROCESSING => 'bg-info',
            self::STATUS_QUOTED => 'bg-primary',
            self::STATUS_CONVERTED => 'bg-success',
            self::STATUS_CLOSED => 'bg-secondary'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inquiry) {
            $inquiry->inquiry_number = 'INQ-' . date('Y') . '-' . str_pad(
                static::whereYear('created_at', date('Y'))->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );
        });
    }
}