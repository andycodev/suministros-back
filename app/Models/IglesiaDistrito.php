<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IglesiaDistrito extends Model
{
    // use Filterable;
    protected $primaryKey = 'id_distrito';
    protected $fillable = [
        'id_distrito',
        'nombre',
        'siglas',
        'activo',
        'id_campo',
        'id_region',
        'id_union',
        'id_organizacion',
    ];

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

    public function iglesias()
    {
        return $this->hasMany(IglesiaIglesia::class, 'id_distrito', 'id_distrito');
    }
}
