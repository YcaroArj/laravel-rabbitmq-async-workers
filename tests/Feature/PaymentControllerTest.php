<?php

namespace Tests\Feature;

use App\Jobs\ProcessTransactionJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_creation_and_idempotency(): void
    {
        Queue::fake();

        Redis::shouldReceive('set')
            ->once()
            ->andReturn(true);

        Redis::shouldReceive('setex')
            ->once()
            ->andReturn(true);

        $payload = [
            'type' => 'pix',
            'amount' => 150.00,
            'customer' => [
                'name' => 'John Doe',
                'document' => '12345678909',
            ],
            'payment_data' => [
                'pix_key' => 'john@doe.com',
            ],
        ];

        $response = $this->postJson('/api/payments', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'type' => 'pix',
            'amount' => 150.00,
            'customer_name' => 'John Doe',
            'customer_document' => '12345678909',
        ]);

        Queue::assertPushed(ProcessTransactionJob::class);
    }

    public function test_payment_idempotency_blocks_duplicate_request(): void
    {
        Queue::fake();
        Redis::shouldReceive('set')
            ->once()
            ->andReturn(false);

        $payload = [
            'type' => 'pix',
            'amount' => 150.00,
            'customer' => [
                'name' => 'John Doe',
                'document' => '12345678909',
            ],
            'payment_data' => [
                'pix_key' => 'john@doe.com',
            ],
        ];

        $response = $this->postJson('/api/payments', $payload);
        $response->assertStatus(200);
        $this->assertDatabaseCount('transactions', 0);
        Queue::assertNotPushed(ProcessTransactionJob::class);
    }
}
