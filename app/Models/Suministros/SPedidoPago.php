<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class SPedidoPago extends Model
{
    protected $table = 's_pedido_pagos';
    protected $primaryKey = 'id_pago';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'codigo',
        'id_persona',
        'total_cantidad',
        'total_monto',
        'estado'
    ];

    protected $casts = [
        'raw_response' => 'array'
    ];
}
