<?php

namespace App\Jobs;

use App\Services\BoletoPaymentService;
use App\Services\CreditCardPaymentService;
use App\Services\PixPaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Exception;

class ProcessTransactionJob implements ShouldQueue
{
    use Queueable;

    private array $transactionData;

    public function __construct(
        public string $paymentId,
        public string $type
    ) {
        $this->onQueue("payment.{$this->type}");
    }

    public function handle(): void
    {
        $processor = match ($this->type) {
            'pix' => app(PixPaymentService::class),
            'boleto' => app(BoletoPaymentService::class),
            'credit_card' => app(CreditCardPaymentService::class),
            default => throw new Exception('Invalid payment type'),
        };
        $processor->process($this->paymentId);
    }

    public function jsonSerialize(): array
    {
        return $this->transactionData;
    }
}
