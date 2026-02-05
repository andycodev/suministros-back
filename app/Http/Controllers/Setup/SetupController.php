<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\IglesiaCampo;
use App\Models\IglesiaDistrito;
use App\Models\IglesiaIglesia;
use App\Models\IglesiaRegion;
use App\Models\IglesiaUnion;

class SetupController extends Controller
{

    public function getIglesiaUnions()
    {
        $unions = IglesiaUnion::all();
        return $unions;
    }

    public function getIglesiaCampos()
    {
        $campos = IglesiaCampo::all();
        return $campos;
    }

    public function getIglesiaRegions()
    {
        $regions = IglesiaRegion::all();
        return $regions;
    }

    public function getIglesiaDistritos()
    {
        $distritos = IglesiaDistrito::all();
        return $distritos;
    }

    public function getIglesiaIglesias()
    {
        $iglesias = IglesiaIglesia::all();
        return $iglesias;
    }

    public function getIglesiaCamposByUnion($id_union)
    {
        $campos = IglesiaCampo::where('id_union', $id_union)->get();
        return $campos;
    }

    public function getIglesiaDistritosByCampo($id_campo)
    {
        $distritos = IglesiaDistrito::where('id_campo', $id_campo)->get();
        return $distritos;
    }

    public function getIglesiaIglesiasByDistrito($id_distrito)
    {
        $iglesias = IglesiaIglesia::where('id_distrito', $id_distrito)->get();
        return $iglesias;
    }
}
