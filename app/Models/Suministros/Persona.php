<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;
use App\Models\IglesiaIglesia;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'id_persona';

    public function iglesia()
    {
        return $this->belongsTo(IglesiaIglesia::class, 'id_iglesia', 'id_iglesia');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_persona', 'id_persona');
    }
}
