<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IglesiaIglesia extends Model
{
    // use Filterable;
    protected $primaryKey = 'id_iglesia';
    protected $fillable = [
        'id_iglesia',
        'nombre',
        'siglas',
        'activo',
        'codigo_iglesia',
        'tipo_congregacion',
        'id_entidad',
        'id_depto',
        'id_region',
        'id_distrito',
        'id_campo',
        'id_union',
        'entidadid_7cloud',
        'clave_natural',
        'id_persona_created'
    ];

    public function distrito()
    {
        return $this->belongsTo(IglesiaDistrito::class, 'id_distrito', 'id_distrito');
    }

    public function campo()
    {
        return $this->belongsTo(IglesiaCampo::class, 'id_campo', 'id_campo');
    }

    public function region()
    {
        return $this->belongsTo(IglesiaRegion::class, 'id_region', 'id_region');
    }

    public function union()
    {
        return $this->belongsTo(IglesiaUnion::class, 'id_union', 'id_union');
    }
}
