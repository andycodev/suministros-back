<?php

namespace App\Models\Suministros;

use Illuminate\Database\Eloquent\Model;

class SPersonaUbicacion extends Model
{
    protected $table = 's_persona_ubicaciones';

    // PK compuesta: Eloquent no soporta directamente, así que:
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = null;

    protected $fillable = [
        'id_persona',
        'id_union',
        'id_campo',
        'id_region',
        'id_distrito',
        'id_iglesia',
        'activo',
        'created_at',
        'updated_at'
    ];

    public function persona()
    {
        return $this->belongsTo(SPersona::class, 'id_persona', 'id_persona');
    }

    public function iglesia()
    {
        return $this->belongsTo(\App\Models\IglesiaIglesia::class, 'id_iglesia', 'id_iglesia');
    }
}
