<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PedidoPago extends Model
{
    use HasFactory;

    protected $table = 'pedido_pagos';
    protected $primaryKey = 'id_pago';

    // Desactivamos timestamps si tu tabla solo usa fecha_pago, 
    // pero si tienes created_at/updated_at, déjalos en true.
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'transaccion_id',
        'monto',
        'metodo_pago',
        'estado_visanet',
        'comprobante_path',
        'raw_response',
        'fecha_pago'
    ];

    protected $casts = [
        'raw_response' => 'array', // Crucial para manejar el JSONB como array de PHP
        'fecha_pago'   => 'datetime',
        'monto'        => 'float'
    ];

    // Relación con el pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    // Al final de tu modelo PedidoPago.php

    protected $appends = ['url_completa_comprobante'];

    public function getUrlCompletaComprobanteAttribute()
    {
        if (!$this->comprobante_path) return null;

        // Esto genera la URL automática: http://tu-dominio/storage/comprobantes/xxx.png
        return asset('storage/' . $this->comprobante_path);
    }
}
