<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'duration_hours' => 'integer',
        'rate' => 'float',
        'amount' => 'float',
        'is_guest' => 'boolean',
    ];

    /**
     * Get the customer name for receipt and notifications.
     */
    public function getCustomerNameAttribute(): string
    {
        return $this->guest_name ?: ($this->player_name ?: 'Guest Player');
    }

    /**
     * Get the customer contact email.
     */
    public function getCustomerEmailAttribute(): ?string
    {
        return $this->guest_email;
    }

    /**
     * Get the customer contact phone.
     */
    public function getCustomerPhoneAttribute(): ?string
    {
        return $this->guest_phone;
    }

    /**
     * Official PlayCricket Pricing:
     * A) Lane hire: R350
     * B) Junior Coaching: R450
     * C) Senior Coaching: R600
     */
    public static function calculateRate(?string $facility = '', ?string $ageGroup = null): float
    {
        $facilityLower = strtolower($facility ?? '');

        // Senior Coaching: R600
        if (
            str_contains($facilityLower, 'senior') || 
            str_contains($facilityLower, 'private') || 
            (str_contains($facilityLower, 'coach') && $ageGroup === 'Above 13')
        ) {
            return 600.00;
        }

        // Junior Coaching: R450
        if (
            str_contains($facilityLower, 'junior') || 
            str_contains($facilityLower, 'academy') || 
            (str_contains($facilityLower, 'coach') && $ageGroup === 'Under 13')
        ) {
            return 450.00;
        }

        // Lane hire (Pitch 1, Pitch 2, Indoor Nets, Bowling Machine): R350
        return 350.00;
    }

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (empty($booking->rate)) {
                $booking->rate = self::calculateRate($booking->facility, $booking->age_group);
            }
            if (empty($booking->amount)) {
                $duration = (float) ($booking->duration_hours ?: 1);
                $booking->amount = round($booking->rate * $duration, 2);
            }
        });
    }

    /**
     * Relationship to payments.
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relationship to latest payment.
     */
    public function latestPayment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
}

