<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales';
    protected $primaryKey = 'id_material';

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'id_material', 'id_material');
    }
}
