<?php

namespace App\Services;

use App\Interfaces\PaymentServiceInterface;

class BoletoPaymentService implements PaymentServiceInterface
{
    public function process(string $transactionId): void
    {

    }

    public function refund(string $id): void
    {
    }
}
