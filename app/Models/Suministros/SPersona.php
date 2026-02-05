<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;
use App\Models\IglesiaIglesia;

class SPersona extends Model
{
    protected $table = 's_personas';
    protected $primaryKey = 'id_persona';

    public function iglesia()
    {
        return $this->belongsTo(IglesiaIglesia::class, 'id_iglesia', 'id_iglesia');
    }

    public function pedidos()
    {
        return $this->hasMany(SPedido::class, 'id_persona', 'id_persona');
    }
}
