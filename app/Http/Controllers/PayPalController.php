<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as TractorRequest;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    /**
     * Registra un pago después de que se haya completado
     */
    public function storePayment(Request $request)
    {
        try {
            // Validar datos recibidos
            $validated = $request->validate([
                'order_id' => 'required|string',
                'request_id' => 'required|exists:requests,id',
                'amount' => 'required|numeric',
                'currency' => 'required|string|size:3',
                'payment_status' => 'required|string',
                'payer_email' => 'nullable|email',
                'payee_email' => 'nullable|email',
                'payment_details' => 'nullable|json',
            ]);

            // Obtener la solicitud relacionada
            $tractorRequest = TractorRequest::findOrFail($validated['request_id']);

            // Verificar que el usuario autenticado es el pagador o el receptor
            if (auth()->id() !== $tractorRequest->requester_id && 
                auth()->id() !== $tractorRequest->listing->seller_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para registrar este pago'
                ], 403);
            }

            // Comprobar si ya existe un pago para esta solicitud y orden
            $existingPayment = Payment::where('request_id', $validated['request_id'])
                ->where('order_id', $validated['order_id'])
                ->first();

            if ($existingPayment) {
                return response()->json([
                    'success' => true,
                    'message' => 'Este pago ya ha sido registrado',
                    'payment' => $existingPayment
                ]);
            }

            // Crear nuevo pago
            $payment = Payment::create([
                'order_id' => $validated['order_id'],
                'request_id' => $validated['request_id'],
                'payer_id' => $tractorRequest->requester_id,
                'payee_id' => $tractorRequest->listing->seller_id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => $validated['payment_status'],
                'payer_email' => $validated['payer_email'] ?? null,
                'payee_email' => $validated['payee_email'] ?? null,
                'payment_details' => $validated['payment_details'] ?? null,
            ]);

            // Actualizar el estado de la solicitud a 'completed' si el pago es exitoso
            if ($payment->status === 'COMPLETED') {
                $tractorRequest->update(['status' => 'completed']);
                
                // Si es una venta, desactivar el anuncio
                if ($tractorRequest->type === 'sale' && $tractorRequest->listing) {
                    $tractorRequest->listing->update(['is_active' => false]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente',
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error('Error al registrar pago de PayPal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener los datos de pago de una solicitud
     */
    public function getPaymentForRequest($requestId)
    {
        try {
            // Obtener la solicitud
            $tractorRequest = TractorRequest::findOrFail($requestId);

            // Verificar que el usuario autenticado tiene acceso a esta solicitud
            if (auth()->id() !== $tractorRequest->requester_id && 
                auth()->id() !== $tractorRequest->listing->seller_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para ver este pago'
                ], 403);
            }

            // Obtener el pago asociado a esta solicitud
            $payment = Payment::where('request_id', $requestId)->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha encontrado ningún pago para esta solicitud'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener pago: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_id' => $requestId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la configuración de PayPal (Client ID, etc.)
     */
    public function getConfig()
    {
        return response()->json([
            'clientId' => config('services.paypal.client_id'),
            'mode' => config('services.paypal.mode'),
            'currency' => config('services.paypal.currency', 'EUR')
        ]);
    }
}