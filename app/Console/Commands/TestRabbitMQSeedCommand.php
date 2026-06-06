<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTransactionJob;
use Illuminate\Console\Command;

class TestRabbitMQSeedCommand extends Command
{
    protected $signature = 'test:rabbitmq {amount=10}';
    protected $description = 'Dispara transações em massa para testar a fila do RabbitMQ';

    public function handle(): void
    {
        $amount = (int) $this->argument('amount');

        $this->info("Iniciando o disparo de {$amount} mensagens para o RabbitMQ...");

        $progressBar = $this->output->createProgressBar($amount);
        $progressBar->start();

        for ($iterator = 1; $iterator <= $amount; $iterator++) {
            $transactionPayload = $this->generateMockTransactionPayload($iterator);
            ProcessTransactionJob::dispatch($transactionPayload);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->components->info("Sucesso! {$amount} mensagens foram enviadas para a fila.");
    }

    private function generateMockTransactionPayload(int $index): array
    {
        $randomIntegerAmount = rand(10, 500);
        $randomCentsAmount = rand(0, 99) / 100;

        return [
            'transaction_id' => uniqid('tx_', true),
            'pix_key' => "teste-{$index}@email.com.br",
            'pix_key_type' => 'email',
            'amount' => (float) ($randomIntegerAmount + $randomCentsAmount),
            'description' => "Teste de carga de mensageria - Mensagem #{$index}"
        ];
    }
}