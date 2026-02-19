<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function notify(Request $request)
    {
        // 1. Validate signature (simplified for this demo, normally use PayFastService)
        // In production, you MUST verify the signature and IP address of PayFast
        
        $paymentId = $request->input('m_payment_id');
        $status = $request->input('payment_status');

        if ($status === 'COMPLETE') {
            $booking = Booking::find($paymentId);
            if ($booking) {
                $booking->update([
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'payment_id' => $request->input('pf_payment_id')
                ]);
            }
        }
        
        return response('OK', 200);
    }

    public function return(Request $request)
    {
        // Redirect to frontend with success query param
        return redirect()->to('/?payment=success');
    }

    public function cancel(Request $request)
    {
        // Redirect to frontend with cancel query param
        return redirect()->to('/?payment=cancelled');
    }
}
