<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Action\HandlePaymentAction;
use App\Http\Requests\StorePaymentRequest;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request): JsonResponse
    {
        try {
            app(HandlePaymentAction::class)->execute($request->validated());
            return response()->json();
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
