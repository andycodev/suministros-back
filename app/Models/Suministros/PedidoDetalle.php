<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $table = 'pedido_detalles';
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
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }
}
