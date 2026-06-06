<?php

namespace App\Interfaces;

interface PaymentTransactionInterface
{
    public function proccess(array $data);
    public function refund(string $id);
}
