<?php

namespace App\Services;

use App\Interfaces\PaymentServiceInterface;
use App\Jobs\ProcessTransactionJob;

class CreditCardPaymentService implements PaymentServiceInterface
{
    public function process(string $transactionId): void
    {

    }

    public function refund(string $id): void
    {
    }
}
