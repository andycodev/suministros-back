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

        $pedidos = SPedido::with(['detalles.material', 'persona', 'destino.iglesia', 'pagos'])
            ->where('id_persona', $request->id_persona)

            ->when($request->tipo, function ($query, $tipo) {
                return $query->where('tipo', $tipo);
            })
            ->when($request->tipo_suscripcion, function ($query, $tipo_suscripcion) {
                return $query->where('tipo_suscripcion', $tipo_suscripcion);
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

    public function getMisPedidosPagos(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|integer'
        ]);

        $pedidos = SPedido::with(['detalles.material', 'persona', 'destino.iglesia', 'pagos'])
            ->where('id_persona', $request->id_persona)
            // Filtro fijo para estados específicos
            ->whereIn('estado', ['PENDIENTE', 'CREADO'])

            ->when($request->tipo, function ($query, $tipo) {
                return $query->where('tipo', $tipo);
            })
            ->when($request->tipo_suscripcion, function ($query, $tipo_suscripcion) {
                return $query->where('tipo_suscripcion', $tipo_suscripcion);
            })
            // Se elimina el filtro dinámico de $request->estado para asegurar la restricción
            ->when($request->codigo, function ($query, $codigo) {
                return $query->where('codigo', 'LIKE', "%{$codigo}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($pedidos->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron pedidos pendientes o creados.'
            ], 200);
        }

        return response()->json($pedidos);
    }
}
