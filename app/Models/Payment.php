<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'request_id',
        'payer_id',
        'payee_id',
        'amount',
        'currency',
        'status',
        'payer_email',
        'payee_email',
        'payment_details',
    ];

    /**
     * Los atributos que deben ser transformados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'json',
    ];

    /**
     * Obtener la solicitud relacionada con este pago.
     */
    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Obtener el pagador (usuario que realiza el pago).
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Obtener el beneficiario (usuario que recibe el pago).
     */
    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}