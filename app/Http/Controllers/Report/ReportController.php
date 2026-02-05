<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Suministros\{
    SPedido,
    SPedidoDetalle,
    SMaterial
};

class ReportController extends Controller
{
    public function getMisPedidos(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|integer'
        ]);

        $pedidos = SPedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_persona', $request->id_persona)

            ->when($request->tipo, function ($query, $tipo) {
                return $query->where('tipo', $tipo);
            })
            ->when($request->modalidad, function ($query, $modalidad) {
                return $query->where('modalidad', $modalidad);
            })
            ->when($request->estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->when($request->codigo, function ($query, $codigo) {
                return $query->where('codigo', 'LIKE', "%{$codigo}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($pedidos->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron pedidos con los filtros seleccionados.'
            ], 200);
        }

        return response()->json($pedidos);
    }
}
