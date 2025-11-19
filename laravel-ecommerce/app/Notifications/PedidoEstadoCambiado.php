<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pedido;

class PedidoEstadoCambiado extends Notification
{
    use Queueable;

    protected $pedido;
    protected $estadoAnterior;

    public function __construct(Pedido $pedido, $estadoAnterior)
    {
        $this->pedido = $pedido;
        $this->estadoAnterior = $estadoAnterior;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mensajes = [
            'pendiente' => [
                'titulo' => '¡Pedido Recibido!',
                'mensaje' => 'Hemos recibido tu pedido y está siendo procesado. Pronto recibirás actualizaciones sobre su estado.',
                'icono' => '📦',
                'color' => '#FFA500'
            ],
            'procesando' => [
                'titulo' => '¡Estamos Preparando tu Pedido!',
                'mensaje' => 'Tu pedido está siendo preparado cuidadosamente por nuestro equipo. Los mejores suplementos están en camino.',
                'icono' => '⚙️',
                'color' => '#3B82F6'
            ],
            'enviado' => [
                'titulo' => '¡Tu Pedido Está en Camino!',
                'mensaje' => 'Tu pedido ha sido enviado y pronto llegará a tu dirección. ¡Prepárate para alcanzar tus metas!',
                'icono' => '🚚',
                'color' => '#8B5CF6'
            ],
            'entregado' => [
                'titulo' => '¡Pedido Entregado!',
                'mensaje' => '¡Tu pedido ha sido entregado exitosamente! Disfruta de tus suplementos y alcanza tus objetivos. Gracias por confiar en NutriShop.',
                'icono' => '✅',
                'color' => '#11BF6E'
            ],
            'cancelado' => [
                'titulo' => 'Pedido Cancelado',
                'mensaje' => 'Tu pedido ha sido cancelado. Si tienes alguna duda o necesitas asistencia, no dudes en contactarnos.',
                'icono' => '❌',
                'color' => '#EF4444'
            ]
        ];

        $info = $mensajes[$this->pedido->estado];

        return (new MailMessage)
            ->subject("NutriShop - {$info['titulo']} - Pedido #{$this->pedido->id}")
            ->view('emails.pedido-estado', [
                'greeting' => "¡Hola {$this->pedido->nombre_completo}!",
                'icono' => $info['icono'],
                'titulo' => $info['titulo'],
                'mensaje' => $info['mensaje'],
                'numeroPedido' => $this->pedido->id,
                'estado' => $this->pedido->estado,
                'total' => $this->pedido->total,
                'fecha' => $this->pedido->created_at->format('d/m/Y H:i'),
                'direccion' => $this->pedido->direccion . ', ' . $this->pedido->ciudad,
                'urlPedido' => route('pedidos.show', $this->pedido),
                'despedida' => 'Gracias por confiar en NutriShop para tus suplementos deportivos.',
                'subject' => "NutriShop - {$info['titulo']}"
            ]);
    }
}
