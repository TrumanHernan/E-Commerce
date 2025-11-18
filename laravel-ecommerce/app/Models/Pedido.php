<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'numero_orden',
        'subtotal',
        'total',
        'estado',
        'nombre_completo',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'codigo_postal',
        'metodo_pago',
        'notas',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pedido) {
            if (!$pedido->numero_orden) {
                $pedido->numero_orden = 'ORD-' . strtoupper(uniqid());
            }
        });
    }
}
