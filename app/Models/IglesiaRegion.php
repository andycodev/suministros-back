<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IglesiaRegion extends Model
{
    // use Filterable;
    protected $primaryKey = 'id_region';
    protected $fillable = [
        'id_region',
        'nombre',
        'siglas',
        'activo',
        'id_campo',
        'id_union',
        'id_organizacion',
    ];

    public function campo()
    {
        return $this->belongsTo(IglesiaCampo::class, 'id_campo', 'id_campo');
    }

    public function union()
    {
        return $this->belongsTo(IglesiaUnion::class, 'id_union', 'id_union');
    }

    public function distritos()
    {
        return $this->hasMany(IglesiaDistrito::class, 'id_region', 'id_region');
    }
}
