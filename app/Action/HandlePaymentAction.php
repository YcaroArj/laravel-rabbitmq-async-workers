<?php

namespace App\Action;

use App\Models\Transactions;
use App\Jobs\ProcessTransactionJob;
use Exception;
use Illuminate\Support\Facades\Cache;

class HandlePaymentAction
{
    public function execute(array $input)
    {
        $key = 'transaction:idempotency:' . md5(json_encode($input));
        if (!Cache::add($key, 'processing', 3600)) {
            return;
        }
        try {
            $transaction = Transactions::create([
                'type' => $input['type'],
                'amount' => $input['amount'],
                'status' => 'pending',
                'customer_name' => $input['customer']['name'] ?? $input['customer_name'] ?? '',
                'customer_document' => $input['customer']['document'] ?? $input['customer_document'] ?? '',
                'payment_data' => $input['payment_data'],
            ]);

            Cache::put($key, $transaction->id, 3600);
            ProcessTransactionJob::dispatch($transaction->id, $transaction->type);
        } catch (Exception $e) {
            Cache::forget($key);
            throw $e;
        }
    }
}