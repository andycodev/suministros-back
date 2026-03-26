<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';
    protected $primaryKey = 'id_periodo';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'es_actual'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_periodo', 'id_periodo');
    }
}
