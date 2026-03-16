<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class SPeriodo extends Model
{
    protected $table = 'public.s_periodos';
    protected $primaryKey = 'id_periodo';
    public $incrementing = false;

    public function pedidos()
    {
        return $this->hasMany(SPedido::class, 'id_periodo', 'id_periodo');
    }
}
