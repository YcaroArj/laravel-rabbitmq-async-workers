<?php

namespace App\Http\Controllers;

use App\Interfaces\PaymentTransactionInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;

abstract class AbstractPaymentController extends Controller
{
    protected LoggerInterface $logger;
    protected PaymentTransactionInterface $service;

    public function __construct(LoggerInterface $logger, PaymentTransactionInterface $service)
    {
        $this->logger = $logger;
        $this->service = $service;
    }

    protected function processPayment(array $validatedData): JsonResponse
    {
        try {
            $this->service->proccess($validatedData);
            $this->logger->info('Transaction queued successfully', ['data' => $validatedData]);
            return response()->json([
                'status' => 'success',
                'message' => 'Transaction queued successfully',
                'data' => $validatedData
            ], 202);
        } catch (Exception $e) {
            $this->logger->error('Failed to queue transaction', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to queue transaction'
            ], 500);
        }
    }
}
