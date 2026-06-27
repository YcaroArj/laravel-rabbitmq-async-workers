<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasUuids;
    protected $table = 'transactions';

    protected $fillable = [
        'id',
        'type',
        'amount',
        'status',
        'customer_name',
        'customer_document',
        'payment_data',
    ];

    protected $casts = [
        'payment_data' => 'array',
    ];
}
