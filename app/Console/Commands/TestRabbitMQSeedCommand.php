<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTransactionJob;
use App\Models\Transactions;
use Illuminate\Console\Command;

class TestRabbitMQSeedCommand extends Command
{
    protected $signature = 'test:rabbitmq {amount=10}';
    protected $description = 'Sends multiple transactions to test RabbitMQ queue';

    public function handle(): void
    {
        $amount = (int) $this->argument('amount');

        $this->info("Starting to send {$amount} messages to RabbitMQ...");

        $progressBar = $this->output->createProgressBar($amount);
        $progressBar->start();

        for ($iterator = 1; $iterator <= $amount; $iterator++) {
            $transaction = Transactions::create([
                'type' => 'pix',
                'amount' => (float) (rand(10, 500) + rand(0, 99) / 100),
                'customer_name' => "Client {$iterator}",
                'customer_document' => '12345678909',
                'status' => 'pending',
                'payment_data' => [
                    'pix_key' => "teste-{$iterator}@email.com.br",
                    'pix_key_type' => 'email',
                    'description' => "Load test - message #{$iterator}"
                ]
            ]);

            ProcessTransactionJob::dispatch($transaction->id, $transaction->type);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->components->info("Successfully sent {$amount} messages to RabbitMQ.");
    }
}