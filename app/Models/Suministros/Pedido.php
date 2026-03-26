<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_persona',
        'id_destino',
        'id_periodo',
        'id_iglesia',
        'codigo',
        'tipo',
        'tipo_suscripcion',
        'total_cantidad',
        'total_monto',
        'saldo_pendiente',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function destino()
    {
        return $this->belongsTo(Persona::class, 'id_destino', 'id_persona');
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'id_pedido', 'id_pedido');
    }

    public function pagos()
    {
        return $this->hasMany(PedidoPago::class, 'id_pedido', 'id_pedido');
    }

    protected $appends = ['total_pagado', 'saldo_pendiente']; // Estos campos se añaden al JSON

    public function getTotalPagadoAttribute()
    {
        // Suma todos los registros de la relación pagos()
        return (float) $this->pagos()->sum('monto');
    }

    public function getSaldoPendienteAttribute()
    {
        // Resta el total del pedido menos lo ya pagado
        return max(0, (float) $this->total_monto - $this->total_pagado);
    }
}
