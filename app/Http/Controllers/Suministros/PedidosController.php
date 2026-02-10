<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Suministros\{
    SPedido,
    SPedidoDetalle,
    SMaterial
};

class PedidosController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_persona'             => 'required|integer|exists:s_personas,id_persona',
            'id_destino'             => 'required|integer|exists:s_personas,id_persona',
            'tipo'                   => 'required|in:P,I',
            'modalidad'              => 'required|in:P,V',
            'detalles'               => 'required|array|min:1',
            'detalles.*.id_material' => 'required|integer|exists:s_materiales,id_material',
            'detalles.*.cantidad'    => 'required|integer|min:1',
        ]);

        $pedido = DB::transaction(function () use ($validated) {

            $pedido = SPedido::create([
                'id_persona'     => $validated['id_persona'],
                'id_destino'     => $validated['id_destino'],
                'codigo'         => 'PE-' . strtoupper(bin2hex(random_bytes(4))),
                'tipo'           => $validated['tipo'],
                'modalidad'      => $validated['modalidad'],
                'total_cantidad' => 0,
                'total_monto'    => 0,
                'estado'         => 'CREADO'
            ]);

            $this->insertarDetalles($pedido, $validated['detalles']);

            return $pedido->load('detalles');
        });

        return response()->json($pedido, 201);
    }


    private function insertarDetalles($pedido, $detalles)
    {
        $totalMonto = 0;
        $totalCant  = 0;

        foreach ($detalles as $item) {

            $material = SMaterial::findOrFail($item['id_material']);

            $cantidad = (int) $item['cantidad'];

            // ⚠️ BLINDAJE DECIMAL PARA POSTGRES
            $precio   = round((float) $material->precio, 2);
            $subtotal = round($precio * $cantidad, 2);

            SPedidoDetalle::create([
                'id_pedido'   => $pedido->id_pedido,
                'id_material' => $item['id_material'],
                'cantidad'    => $cantidad,
                'precio_unit' => $precio,
                'subtotal'    => $subtotal
            ]);

            $totalMonto += $subtotal;
            $totalCant  += $cantidad;
        }

        // ⚠️ también redondeamos el total final
        $pedido->update([
            'total_monto'    => round($totalMonto, 2),
            'total_cantidad' => $totalCant
        ]);
    }


    public function showPedidoByIdPedido($id_pedido)
    {
        $pedido = SPedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_pedido', $id_pedido)
            ->first();

        return response()->json($pedido ?? []);
    }

    public function showPedidoByIdPersona($id_persona)
    {
        $pedidos = SPedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_persona', $id_persona)
            ->where('estado', 'CREADO')
            ->whereIn('tipo', ['P', 'I'])
            ->get();

        return response()->json($pedidos);
    }

    public function showPedidoByIdDestino($id_destino)
    {
        $pedidos = SPedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_destino', $id_destino)
            ->where('estado', 'CREADO')
            ->whereIn('tipo', ['P', 'I'])
            ->get();

        return response()->json($pedidos);
    }

    /*  public function showPedidoByIdPersona($id_persona)
    {
        $pedido = SPedido::with(['detalles.material', 'persona'])
            ->where('id_persona', $id_persona)
            ->where('estado', 'CREADO')
            ->first();

        return response()->json($pedido ?: []);
    } */

    public function showPedidoByCodigo($codigo)
    {
        $pedido = SPedido::with(['detalles.material', 'persona'])
            ->where('codigo', $codigo)
            ->first();

        return response()->json($pedido ?? []);
    }

    /* public function estadoPorCodigo($codigo)
    {
        $pedido = SPedido::where('codigo', $codigo)->firstOrFail();
        return response()->json(['estado' => $pedido->estado]);
    } */
}
