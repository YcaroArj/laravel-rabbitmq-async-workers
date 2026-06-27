<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Interfaces\PaymentServiceInterface;
use App\Models\Transactions;

class PixPaymentService implements PaymentServiceInterface
{
    public function process(string $transactionId): void
    {
        $transaction = Transactions::find($transactionId);
        $transaction->update([
            'status' => PaymentStatus::APPROVED
        ]);
    }

    public function refund(string $id): void
    {
    }
}
