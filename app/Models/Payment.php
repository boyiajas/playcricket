<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    /**
     * Relationship to the Booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Polymorphic relation for flexible payments (Bookings, Academy, Memberships).
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope query to completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope query to pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
