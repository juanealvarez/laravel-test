<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Transaction;
use App\Models\PaymentRequestLog;

// Services
use App\Services\EasyMoneyPaymentService;
use App\Services\SuperWalletzPaymentService;

class PaymentController extends Controller
{
    private EasyMoneyPaymentService $easyMoneyService;

    private SuperWalletzPaymentService $superWalletzService;

    public function __construct(
        EasyMoneyPaymentService $easyMoneyService,
        SuperWalletzPaymentService $superWalletzService
    ) {
        $this->easyMoneyService = $easyMoneyService;
        $this->superWalletzService = $superWalletzService;
    }

    public function payEasyMoney(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if amount is an integer since EasyMoney doesn't support decimals
        if (!is_int($request->amount) && floor($request->amount) != $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'EasyMoney only accepts integer amounts'
            ], 422);
        }

        try {
            // Process payment
            $result = $this->easyMoneyService->processPayment(
                $request->amount,
                $request->currency
            );

            // Create transaction record
            $transaction = Transaction::create([
                'provider' => 'easymoney',
                'amount' => $request->amount,
                'currency' => $request->currency,
                'external_id' => null,
                'status' => $result === 'ok' ? 'success' : 'failed'
            ]);

            // Log the request and response
            PaymentRequestLog::create([
                'provider' => 'easymoney',
                'request_body' => json_encode([
                    'amount' => $request->amount,
                    'currency' => $request->currency
                ]),
                'response_body' => $result
            ]);

            if ($result === 'ok') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully'
                ]);
            }

            throw new \Exception('Payment processing failed');
        } catch (\Exception $e) {
            // Log the error if any exception occurs
            \Log::error('EasyMoney payment error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function paySuperWalletz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'callback_url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->superWalletzService->processPayment(
                $request->amount,
                $request->currency,
                $request->callback_url
            );

            // Posibles respuestas:
            // [
            //    "transaction_id" => "trx_75813"
            //]

            Transaction::create([
                'provider' => 'superwalletz',
                'amount' => $request->amount,
                'currency' => $request->currency,
                'external_id' => $result['transaction_id'],
                'status' => 'success'
            ]);

            PaymentRequestLog::create([
                'provider' => 'superwalletz',
                'request_body' => json_encode([
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'callback_url' => $request->callback_url
                ]),
                'response_body' => json_encode($result)
            ]);

            if ($result['transaction_id']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully'
                ]);
            }

            throw new \Exception('Payment processing failed');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
