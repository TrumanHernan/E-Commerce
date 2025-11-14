<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'precio_oferta',
        'stock',
        'imagen',
        'slug',
        'categoria_id',
        'activo',
        'destacado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'activo' => 'boolean',
        'destacado' => 'boolean',
    ];

    /**
     * Relación: Un producto pertenece a una categoría
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Accessor para obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute(): string
    {
        return $this->imagen
            ? asset('storage/productos/' . $this->imagen)
            : asset('images/placeholder-producto.jpg');
    }

    /**
     * Accessor para determinar si el producto tiene oferta
     */
    public function getTieneOfertaAttribute(): bool
    {
        return $this->precio_oferta !== null && $this->precio_oferta < $this->precio;
    }
}
