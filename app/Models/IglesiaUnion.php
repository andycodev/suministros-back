<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IglesiaUnion extends Model
{
    // use Filterable;
    protected $primaryKey = 'id_union';
    protected $fillable = [
        'id_union',
        'nombre',
        'siglas',
        'activo',
        'orden',
        'id_organizacion',
    ];

    public function campos()
    {
        return $this->hasMany(IglesiaCampo::class, 'id_union', 'id_union');
    }

    public function regiones()
    {
        return $this->hasMany(IglesiaRegion::class, 'id_union', 'id_union');
    }

    public function distritos()
    {
        return $this->hasMany(IglesiaDistrito::class, 'id_union', 'id_union');
    }

    public function iglesias()
    {
        return $this->hasMany(IglesiaIglesia::class, 'id_union', 'id_union');
    }
}
