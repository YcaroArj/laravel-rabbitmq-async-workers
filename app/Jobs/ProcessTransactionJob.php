<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessTransactionJob implements ShouldQueue
{
    use Queueable;

    private array $transactionData;

    public function __construct(array $transactionData)
    {
        $this->transactionData = $transactionData;
    }

    public function handle(): void
    {
        $payload = json_encode($this->transactionData);
        logger()->info("Processing transaction via worker: {$payload}");
    }

    public function jsonSerialize(): array
    {
        return $this->transactionData;
    }
}
