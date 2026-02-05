<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class SMaterial extends Model
{
    protected $table = 's_materiales';
    protected $primaryKey = 'id_material';

    public function detalles()
    {
        return $this->hasMany(SPedidoDetalle::class, 'id_material', 'id_material');
    }
}
