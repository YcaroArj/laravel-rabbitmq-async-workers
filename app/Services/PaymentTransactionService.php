<?php

namespace App\Services;

use App\Jobs\ProcessTransactionJob;
use App\Interfaces\PaymentTransactionInterface;

class PaymentTransactionService implements PaymentTransactionInterface
{
    public function proccess(array $data)
    {
        ProcessTransactionJob::dispatch($data);
    }

    public function refund(string $id)
    {
    }
}