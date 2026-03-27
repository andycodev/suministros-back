<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\IglesiaCampo;
use App\Models\IglesiaDistrito;
use App\Models\IglesiaIglesia;
use App\Models\IglesiaRegion;
use App\Models\IglesiaUnion;
use App\Models\Suministros\Periodo;

class SetupController extends Controller
{

    public function getPeriodos()
    {
        $periodos = Periodo::orderBy('id_periodo', 'asc')
            ->where('activo', true)
            ->get();

        return $this->successResponse($periodos);
    }

    public function getIglesiaUnions()
    {
        $unions = IglesiaUnion::all();
        return $this->successResponse($unions);
    }

    public function getIglesiaCampos()
    {
        $campos = IglesiaCampo::all();
        return $this->successResponse($campos);
    }

    public function getIglesiaRegions()
    {
        $regions = IglesiaRegion::all();
        return $this->successResponse($regions);
    }

    public function getIglesiaDistritos()
    {
        $distritos = IglesiaDistrito::all();
        return $this->successResponse($distritos);
    }

    public function getIglesiaIglesias()
    {
        $iglesias = IglesiaIglesia::all();
        return $this->successResponse($iglesias);
    }

    public function getIglesiaCamposByUnion($id_union)
    {
        $campos = IglesiaCampo::where('id_union', $id_union)->get();
        return $this->successResponse($campos);
    }

    public function getIglesiaDistritosByCampo($id_campo)
    {
        $distritos = IglesiaDistrito::where('id_campo', $id_campo)->get();
        return $this->successResponse($distritos);
    }

    public function getIglesiaIglesiasByDistrito($id_distrito)
    {
        $iglesias = IglesiaIglesia::where('id_distrito', $id_distrito)->get();
        return $this->successResponse($iglesias);
    }
}
