<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequestModel $request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $request = $this->request->load(['listing.tractor', 'requester']);
        $listing = $request->listing;
        $requester = $request->requester;
        
        return (new MailMessage)
            ->subject('Nueva solicitud para tu anuncio')
            ->greeting('Hola ' . $notifiable->first_name)
            ->line('Has recibido una nueva solicitud para tu anuncio.')
            ->line('Detalles del anuncio:')
            ->line('Tipo: ' . ($listing->type === 'sale' ? 'Venta' : 'Alquiler'))
            ->line('Tractor: ' . ($listing->tractor ? $listing->tractor->brand . ' ' . $listing->tractor->model : 'No especificado'))
            ->line('Precio: ' . number_format($listing->price, 2, ',', '.') . '€')
            ->line('Solicitante: ' . $requester->first_name . ' ' . $requester->last_name)
            ->line('Mensaje: ' . ($request->message ?: 'No hay mensaje'))
            ->action('Ver solicitud', url(route('listings.requests', $listing->id)))
            ->line('Gracias por usar TractorApp!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $request = $this->request->load(['listing.tractor', 'requester']);
        $listing = $request->listing;
        $requester = $request->requester;
        
        return [
            'request_id' => $request->id,
            'listing_id' => $listing->id,
            'tractor_info' => $listing->tractor ? $listing->tractor->brand . ' ' . $listing->tractor->model : 'Tractor',
            'requester_name' => $requester->first_name . ' ' . $requester->last_name,
            'requester_id' => $requester->id,
            'message' => $request->message,
            'listing_type' => $listing->type,
            'price' => $listing->price,
        ];
    }
}