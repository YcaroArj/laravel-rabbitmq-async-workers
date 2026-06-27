<?php

namespace App\Interfaces;

interface PaymentServiceInterface
{
    public function process(string $transactionId): void;

    public function refund(string $id): void;
}