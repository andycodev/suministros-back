<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\{SPedido, SPedidoPago, SPedidoEstado};

class PagarController extends Controller
{
    /**
     * Webhook VisaNet (IPN)
     */
    public function webhookVisaNet(Request $request)
    {
        $codigo = $request->codigo_pedido;
        $estado = $request->estado;

        $pedido = SPedido::where('codigo', $codigo)->first();

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        // Registrar pago
        SPedidoPago::create([
            'id_pedido'      => $pedido->id_pedido,
            'transaccion_id' => $request->transaccion_id,
            'monto'          => $pedido->total_monto,
            'estado_visanet' => $estado,
            'raw_response'   => $request->all()
        ]);

        // Actualizar pedido
        if ($estado === 'OK') {
            $pedido->update(['estado' => 'PAGADO']);
        }

        SPedidoEstado::create([
            'id_pedido' => $pedido->id_pedido,
            'estado'    => $estado,
            'descripcion' => 'Webhook VisaNet'
        ]);

        return response()->json(['message' => 'Webhook procesado']);
    }
}
