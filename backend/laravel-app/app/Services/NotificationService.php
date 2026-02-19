<?php

namespace App\Services;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send booking confirmation.
     *
     * @param Booking $booking
     * @param string $email
     */
    public function sendBookingConfirmation(Booking $booking, string $email)
    {
        // 1. Send Email (Laravel Mailable)
        // In a real app, ensure MAIL_ settings are correct.
        // For development, we log if it fails or use 'log' driver.
        try {
             Mail::to($email)->send(new BookingConfirmation($booking));
             Log::info("Email confirmation sent to {$email} for booking {$booking->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send email confirmation: " . $e->getMessage());
        }

        // 2. Send WhatsApp (Stub)
        $this->sendWhatsAppNotification($booking);
    }

    /**
     * Stub for WhatsApp notification.
     *
     * @param Booking $booking
     */
    protected function sendWhatsAppNotification(Booking $booking)
    {
        // Requires Twilio/Meta API integration
        // For now, we log the message that WOULD be sent.
        $message = "🏏 Booking Confirmed! ID: {$booking->id}. Facility: {$booking->facility}. Date: {$booking->date} @ {$booking->start_time}. Thanks for using PlayCricket!";
        Log::info("WhatsApp Notification Stub: {$message}");
    }
}
