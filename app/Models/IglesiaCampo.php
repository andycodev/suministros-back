<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IglesiaCampo extends Model
{
    // use Filterable;
    protected $primaryKey = 'id_campo';
    protected $fillable = [
        'id_campo',
        'nombre',
        'siglas',
        'activo',
        'id_union',
        'id_organizacion',
    ];

    public function union()
    {
        return $this->belongsTo(IglesiaUnion::class, 'id_union', 'id_union');
    }

    public function regiones()
    {
        return $this->hasMany(IglesiaRegion::class, 'id_campo', 'id_campo');
    }

    public function distritos()
    {
        return $this->hasMany(IglesiaDistrito::class, 'id_campo', 'id_campo');
    }

    public function iglesias()
    {
        return $this->hasMany(IglesiaIglesia::class, 'id_campo', 'id_campo');
    }
}
