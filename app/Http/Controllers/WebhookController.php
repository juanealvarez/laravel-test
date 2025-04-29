<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WebhookLog;
use App\Models\Transaction;

use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handler(Request $request)
    {
        Log::info('Webhook received', $request->all());
        //Guardamos los datos del webhook en la base de datos
        WebhookLog::create([
            'provider' => "superwalletz",
            'payload' => json_encode($request->all())
        ]);

        // Buscamos la transacción en la base de datos
        $transaction = Transaction::where('external_id', $request->transaction_id)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }

        // Actualizamos el estado de la transacción
        $transaction->status = $request->status;
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'Webhook received successfully'
        ]);
    }
}
