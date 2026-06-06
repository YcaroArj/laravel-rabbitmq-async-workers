<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePixRequestTransaction;
use App\Interfaces\PaymentTransactionInterface;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;

class PixController extends AbstractPaymentController
{
    public function __construct(LoggerInterface $logger, PaymentTransactionInterface $service)
    {
        parent::__construct($logger, $service);
    }

    public function store(StorePixRequestTransaction $request): JsonResponse
    {
        $this->logger->info('Starting Pix transaction processing.');
        return $this->processPayment($request->validated());
    }
}
