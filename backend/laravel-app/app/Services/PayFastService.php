<?php

namespace App\Services;

class PayFastService
{
    private $merchantId;
    private $merchantKey;
    private $passphrase;
    private $env;

    public function __construct()
    {
        // Ideally these should come from config/services.php
        $this->merchantId = '10000100'; // Sandbox Merchant ID
        $this->merchantKey = '46f0cd694581a'; // Sandbox Merchant Key
        $this->passphrase = 'jt7NOE43FZPn'; // Sandbox Passphrase
        $this->env = 'sandbox'; // or 'production'
    }

    public function generatePaymentForm($booking, $returnUrl, $cancelUrl, $notifyUrl)
    {
        // Price hardcoded for demo: $100 per hour
        $amount = $booking->duration_hours * 100.00; 
        
        $data = [
            // Merchant details
            'merchant_id' => $this->merchantId,
            'merchant_key' => $this->merchantKey,
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'notify_url' => $notifyUrl,

            // Buyer details (mocked for now, normally from Auth::user())
            'name_first' => 'Test',
            'name_last'  => 'User',
            'email_address'=> 'test@test.com', 

            // Transaction details
            'm_payment_id' => $booking->id, // Unique payment ID to match in ITN
            'amount' => number_format($amount, 2, '.', ''),
            'item_name' => 'Booking: ' . $booking->facility . ' (' . $booking->date->format('Y-m-d') . ')',
        ];

        // Generate signature
        $data['signature'] = $this->generateSignature($data);

        return [
            'url' => $this->env === 'sandbox' ? 'https://sandbox.payfast.co.za/eng/process' : 'https://www.payfast.co.za/eng/process',
            'data' => $data
        ];
    }

    private function generateSignature($data)
    {
        // 1. Create parameter string
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }
        
        // 2. Remove last ampersand
        $getString = substr($pfOutput, 0, -1);
        
        // 3. Add passphrase
        if ($this->passphrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($this->passphrase));
        }
        
        // 4. MD5 Hash
        return md5($getString);
    }
}
