<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class SPedido extends Model
{
    protected $table = 's_pedidos';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_persona',
        'id_destino',
        'codigo',
        'tipo',
        'modalidad',
        'total_cantidad',
        'total_monto',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(SPersona::class, 'id_persona', 'id_persona');
    }

    public function destino()
    {
        return $this->belongsTo(SPersona::class, 'id_destino', 'id_persona');
    }

    public function detalles()
    {
        return $this->hasMany(SPedidoDetalle::class, 'id_pedido', 'id_pedido');
    }
}
