<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as TractorRequest;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
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

    /**
     * Registra un pago después de que se haya completado entre usuarios
     * Solo almacena la información del pago, no procesa el pago en sí
     */
    public function storePayment(Request $request)
    {
        try {
            Log::info('Recibida solicitud de registro de pago', $request->all());
            
            // Validar datos recibidos
            $validated = $request->validate([
                'order_id' => 'required|string',
                'request_id' => 'required|exists:requests,id',
                'amount' => 'required|numeric',
                'currency' => 'required|string|size:3',
                'payment_status' => 'required|string',
                'payer_email' => 'nullable|email',
                'payee_email' => 'nullable|email',
                'payment_details' => 'nullable',
            ]);

            // Obtener la solicitud relacionada
            $tractorRequest = TractorRequest::findOrFail($validated['request_id']);

            // Verificar que el usuario autenticado es parte de la transacción
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

            // Validar que JSON sea válido o convertirlo a string
            $paymentDetails = $validated['payment_details'];
            if (is_array($paymentDetails) || is_object($paymentDetails)) {
                $paymentDetails = json_encode($paymentDetails);
            }

            // Crear nuevo registro de pago (solo registro, no procesamiento)
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
                'payment_details' => $paymentDetails,
            ]);

            // Actualizar el estado de la solicitud a 'completed' si el pago es exitoso
            // Este es solo un cambio de estado en nuestra plataforma, no afecta al pago real
            if ($payment->status === 'COMPLETED') {
                $tractorRequest->update(['status' => 'completed']);
                
                // Si es una venta, desactivar el anuncio
                if ($tractorRequest->type === 'sale' && $tractorRequest->listing) {
                    $tractorRequest->listing->update(['is_active' => false]);
                }
            }

            // Enviar notificación a los usuarios involucrados
            $this->notifyPaymentParticipants($payment);

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
     * Notificar a los participantes del pago
     */
    private function notifyPaymentParticipants($payment)
    {
        try {
            // Obtener la solicitud relacionada
            $request = TractorRequest::with(['requester', 'listing.seller', 'listing.tractor'])
                ->findOrFail($payment->request_id);

            // Notificar al comprador
            if ($request->requester) {
                // Aquí se implementaría la lógica para enviar una notificación al comprador
                // Por ejemplo, un correo electrónico o una notificación push
                Log::info('Notificación enviada al comprador', [
                    'user_id' => $request->requester->id,
                    'payment_id' => $payment->id
                ]);
            }

            // Notificar al vendedor
            if ($request->listing && $request->listing->seller) {
                // Aquí se implementaría la lógica para enviar una notificación al vendedor
                Log::info('Notificación enviada al vendedor', [
                    'user_id' => $request->listing->seller->id,
                    'payment_id' => $payment->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error al enviar notificaciones de pago: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'trace' => $e->getTraceAsString()
            ]);
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
     * Obtener el email del vendedor para un pago directo P2P
     * Soporta múltiples vendedores en sandbox y producción
     */
    public function getSellerPaymentInfo($requestId)
    {
        try {
            // Obtener la solicitud con el vendedor
            $tractorRequest = TractorRequest::with(['listing.seller', 'listing.tractor'])
                ->findOrFail($requestId);

            // Verificar que el usuario autenticado es el comprador
            if (auth()->id() !== $tractorRequest->requester_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para obtener esta información'
                ], 403);
            }

            // Verificar que la solicitud está aceptada
            if ($tractorRequest->status !== 'accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta solicitud no está aceptada para pago'
                ], 400);
            }

            // Obtener la información del vendedor
            $seller = $tractorRequest->listing->seller;
            
            if (!$seller || !$seller->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha encontrado información de pago del vendedor'
                ], 404);
            }

            // Preparar información del producto
            $itemName = 'Maquinaria agrícola';
            if ($tractorRequest->listing->tractor) {
                $itemName = $tractorRequest->listing->tractor->brand . ' ' . $tractorRequest->listing->tractor->model;
            }

            // Comprobar si estamos en modo sandbox o producción
            $isSandbox = config('services.paypal.mode') === 'sandbox';
            
            // Obtener el email del vendedor para el pago
            $payeeEmail = $seller->email;
            
            // En modo sandbox, verificar si el vendedor tiene una cuenta sandbox asignada
            if ($isSandbox) {
                // Buscar si el vendedor tiene un perfil de sandbox registrado
                $sandboxEmail = $this->getSandboxEmailForSeller($seller->id);
                
                if ($sandboxEmail) {
                    // Si tiene una cuenta sandbox asignada, usarla
                    $payeeEmail = $sandboxEmail;
                } else {
                    // Si no tiene cuenta sandbox asignada, usar la cuenta por defecto
                    $payeeEmail = config('services.paypal.sandbox_default_account');
                    
                    // Si tampoco hay cuenta por defecto configurada, advertir
                    if (!$payeeEmail) {
                        Log::warning('No hay cuenta sandbox por defecto configurada para vendedores', [
                            'seller_id' => $seller->id,
                            'request_id' => $requestId
                        ]);
                        
                        // Usar un valor que al menos funcione en sandbox
                        $payeeEmail = 'sb-manol@business.example.com';
                    }
                }
            }
            
            // Retornar la información necesaria para el pago
            return response()->json([
                'success' => true,
                'seller_info' => [
                    'email' => $payeeEmail,
                    'seller_id' => $seller->id,
                    'request_id' => $tractorRequest->id,
                    'amount' => $tractorRequest->offered_price ?: $tractorRequest->listing->price,
                    'item_name' => $itemName,
                    'is_sandbox' => $isSandbox
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener información de pago del vendedor: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_id' => $requestId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información de pago: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener el email de sandbox para un vendedor específico
     * Este método se puede ampliar con una tabla en la base de datos que relacione
     * usuarios reales con sus cuentas de sandbox
     */
    private function getSandboxEmailForSeller($sellerId)
    {
        // Implementación básica: Buscar en un array de mapeo
        // Esto se podría reemplazar por una tabla en la base de datos
        $sandboxAccounts = [
            // ID del vendedor => Email de sandbox
            1 => 'sb-vendedor1@business.example.com',
            2 => 'sb-vendedor2@business.example.com',
            3 => 'sb-vendedor3@business.example.com',
            // Añadir más mapeos según sea necesario
        ];
        
        // Retornar el email de sandbox si existe, o null si no
        return $sandboxAccounts[$sellerId] ?? null;
    }
}