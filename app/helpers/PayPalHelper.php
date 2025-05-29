<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class PayPalHelper
{
    /**
     * Obtener el email de PayPal para un vendedor específico, según el entorno
     *
     * @param User $seller El usuario vendedor
     * @return string|null El email de PayPal para pagos
     */
    public static function getSellerPayPalEmail(User $seller)
    {
        try {
            // En producción, usar el email real del vendedor
            if (config('paypal.mode') === 'live') {
                return $seller->email; // Usamos el email normal del usuario
            }

            // En sandbox, buscamos si tiene una cuenta asignada en la configuración
            $sandboxAccounts = config('paypal.seller_sandbox_accounts', []);
            
            if (isset($sandboxAccounts[$seller->id])) {
                return $sandboxAccounts[$seller->id];
            }
            
            // Si no tiene cuenta asignada, usar la cuenta por defecto
            return config('paypal.sandbox_default_seller_account');
            
        } catch (\Exception $e) {
            Log::error('Error al obtener email de PayPal del vendedor: ' . $e->getMessage(), [
                'seller_id' => $seller->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
    
    /**
     * Obtener el email de PayPal para un comprador específico, según el entorno
     *
     * @param User $buyer El usuario comprador
     * @return string|null El email de PayPal para pagos
     */
    public static function getBuyerPayPalEmail(User $buyer)
    {
        try {
            // En producción, usar el email real del comprador
            if (config('paypal.mode') === 'live') {
                return $buyer->email; // Usamos el email normal del usuario
            }

            // En sandbox, buscamos si tiene una cuenta asignada en la configuración
            $sandboxAccounts = config('paypal.buyer_sandbox_accounts', []);
            
            if (isset($sandboxAccounts[$buyer->id])) {
                return $sandboxAccounts[$buyer->id];
            }
            
            // Si no tiene cuenta asignada, usar la cuenta por defecto
            return config('paypal.sandbox_default_buyer_account');
            
        } catch (\Exception $e) {
            Log::error('Error al obtener email de PayPal del comprador: ' . $e->getMessage(), [
                'buyer_id' => $buyer->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
    
    /**
     * Verificar si un usuario tiene configuración de PayPal como vendedor
     *
     * @param User $seller El usuario vendedor
     * @return bool Si el vendedor tiene configuración de PayPal
     */
    public static function hasSellerPayPalConfiguration(User $seller)
    {
        // En producción, verificar que tenga email
        if (config('paypal.mode') === 'live') {
            return !empty($seller->email);
        }
        
        // En sandbox, verificar si tiene cuenta en la configuración o si hay cuenta por defecto
        $sandboxAccounts = config('paypal.seller_sandbox_accounts', []);
        
        return isset($sandboxAccounts[$seller->id]) || !empty(config('paypal.sandbox_default_seller_account'));
    }
    
    /**
     * Verificar si un usuario tiene configuración de PayPal como comprador
     *
     * @param User $buyer El usuario comprador
     * @return bool Si el comprador tiene configuración de PayPal
     */
    public static function hasBuyerPayPalConfiguration(User $buyer)
    {
        // En producción, verificar que tenga email
        if (config('paypal.mode') === 'live') {
            return !empty($buyer->email);
        }
        
        // En sandbox, verificar si tiene cuenta en la configuración o si hay cuenta por defecto
        $sandboxAccounts = config('paypal.buyer_sandbox_accounts', []);
        
        return isset($sandboxAccounts[$buyer->id]) || !empty(config('paypal.sandbox_default_buyer_account'));
    }
}