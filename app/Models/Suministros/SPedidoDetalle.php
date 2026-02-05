<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class SPedidoDetalle extends Model
{
    protected $table = 's_pedido_detalles';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_pedido',
        'id_material',
        'cantidad',
        'precio_unit',
        'subtotal'
    ];

    public function pedido()
    {
        return $this->belongsTo(SPedido::class, 'id_pedido', 'id_pedido');
    }

    public function material()
    {
        return $this->belongsTo(SMaterial::class, 'id_material', 'id_material');
    }
}
