<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use App\Models\Suministros\Periodo;

class PeriodoController extends Controller
{
    public function getPeriodos()
    {
        $periodos = Periodo::orderBy('id_periodo', 'asc')
            ->where('activo', true)
            ->get();

        return $this->successResponse($periodos);
    }
}
