<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Suministros\{
    Pedido,
    PedidoDetalle,
    Material,
    PedidoPago,
    Periodo
};
use Exception;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_persona'       => 'required|integer|exists:personas,id_persona',
            'id_destino'       => 'required|integer|exists:personas,id_persona',
            'id_periodo'       => [
                'required',
                'integer',
                'exists:periodos,id_periodo',
                Rule::unique('pedidos', 'id_periodo')->where(function ($query) use ($request) {
                    return $query->where('id_persona', $request->id_persona)
                        ->where('id_destino', $request->id_destino)
                        ->where('tipo', $request->tipo);
                }),
            ],
            'id_iglesia'       => 'nullable|integer|exists:iglesia_iglesias,id_iglesia',
            'tipo'             => 'required|in:P,I', //P: Personal I:Iglesia
            'tipo_suscripcion' => 'required|in:F,V', //F: Fisico V: Virtual
            'detalles'         => 'required|array|min:1',
            'detalles.*.id_material' => 'required|integer|exists:materiales,id_material',
            'detalles.*.cantidad'    => 'required|integer|min:1',
        ], [
            'id_periodo.unique' => 'Ya existe un pedido de tipo ' . ($request->tipo == 'P' ? 'Personal' : 'Iglesia') . ' para este destino en el periodo seleccionado.'
        ]);

        $pedido = DB::transaction(function () use ($validated) {
            $pedido = Pedido::create([
                'id_persona'       => $validated['id_persona'],
                'id_destino'       => $validated['id_destino'],
                'id_periodo'       => $validated['id_periodo'],
                'id_iglesia'       => $validated['id_iglesia'],
                'codigo'           => 'PE-' . strtoupper(bin2hex(random_bytes(4))),
                'tipo'             => $validated['tipo'],
                'tipo_suscripcion' => $validated['tipo_suscripcion'],
                'total_cantidad'   => 0,
                'total_monto'      => 0,
                'estado'           => 'CREADO'
            ]);

            $this->insertarDetalles($pedido, $validated['detalles']);

            return $pedido->load('detalles');
        });

        return $this->successResponse($pedido, 'Pedido creado correctamente');
    }

    public function update(Request $request, $id)
    {
        // Buscamos el pedido
        $pedido = Pedido::findOrFail($id);

        $validated = $request->validate([
            'id_persona'       => 'required|integer|exists:personas,id_persona',
            'id_destino'       => 'required|integer|exists:personas,id_persona',
            'id_periodo'       => [
                'required',
                'integer',
                'exists:periodos,id_periodo',
                // Regla de unicidad ignorando el registro actual
                Rule::unique('pedidos', 'id_periodo')
                    ->ignore($pedido->id_pedido, 'id_pedido') // Ignora este pedido
                    ->where(function ($query) use ($request) {
                        return $query->where('id_persona', $request->id_persona)
                            ->where('id_destino', $request->id_destino)
                            ->where('tipo', $request->tipo);
                    }),
            ],
            'id_iglesia'       => 'nullable|integer|exists:iglesia_iglesias,id_iglesia',
            'tipo'             => 'required|in:P,I',
            'tipo_suscripcion' => 'required|in:F,V',
            'detalles'         => 'required|array|min:1',
            'detalles.*.id_material' => 'required|integer|exists:materiales,id_material',
            'detalles.*.cantidad'    => 'required|integer|min:1',
        ], [
            'id_periodo.unique' => 'Ya existe otro pedido de tipo ' . ($request->tipo == 'P' ? 'Personal' : 'Iglesia') . ' para este destino en el periodo seleccionado.'
        ]);

        $pedido = DB::transaction(function () use ($pedido, $validated) {
            // 1. Actualizar los datos básicos del pedido
            $pedido->update([
                'id_persona'       => $validated['id_persona'],
                'id_destino'       => $validated['id_destino'],
                'id_periodo'       => $validated['id_periodo'],
                'id_iglesia'       => $validated['id_iglesia'],
                'tipo'             => $validated['tipo'],
                'tipo_suscripcion' => $validated['tipo_suscripcion'],
            ]);

            // 2. Sincronizar detalles: Eliminamos los anteriores
            $pedido->detalles()->delete();

            // 3. Insertar los nuevos detalles usando tu función existente
            $this->insertarDetalles($pedido, $validated['detalles']);

            return $pedido->fresh('detalles');
        });

        return $this->successResponse($pedido, 'Pedido actualizado correctamente');
    }

    private function insertarDetalles($pedido, $detalles)
    {
        $totalMonto = 0;
        $totalCant  = 0;

        foreach ($detalles as $item) {
            $material = Material::findOrFail($item['id_material']);

            $cantidad = (int)$item['cantidad'];

            $subtotal = $material->precio * $cantidad;

            PedidoDetalle::create([
                'id_pedido'   => $pedido->id_pedido,
                'id_material' => $item['id_material'],
                'cantidad'    => $cantidad,
                'precio_unit' => $material->precio,
                'subtotal'    => $subtotal
            ]);

            $totalMonto += $subtotal;
            $totalCant  += $cantidad;
        }

        $pedido->update([
            'total_monto'    => $totalMonto,
            'total_cantidad' => $totalCant
        ]);
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);

        DB::transaction(function () use ($pedido) {
            $pedido->detalles()->delete();
            $pedido->delete();
        });

        return $this->successResponse(null, 'Pedido eliminado con éxito.');
    }

    public function showPedidoByIdPedido($id_pedido)
    {
        $pedido = Pedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_pedido', $id_pedido)
            ->first();

        return $this->successResponse($pedido ?? [], 'Pedido obtenido correctamente');
    }

    public function showPedidoByIdPersona($id_persona)
    {
        $pedidos = Pedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_persona', $id_persona)
            ->where('estado', 'CREADO')
            ->whereIn('tipo', ['P', 'I'])
            ->get();

        return $this->successResponse($pedidos, 'Pedidos obtenidos correctamente');
    }

    public function showPedidoByIdDestino($id_destino)
    {
        $pedidos = Pedido::with(['detalles.material', 'persona', 'destino'])
            ->where('id_destino', $id_destino)
            ->where('estado', 'CREADO')
            ->whereIn('tipo', ['P', 'I'])
            ->get();

        return $this->successResponse($pedidos);
    }

    // Solo traer campos necesarios de las relaciones
    /*  public function showPedidoByIdDestino($id_destino)
    {
        $pedidos = Pedido::with([
            'persona' => function ($query) {
                $query->select('id_persona', 'nombres', 'ap_paterno', 'ap_materno');
            },
            'destino' => function ($query) {
                $query->select('id_persona', 'nombres', 'ap_paterno');
            }
        ])
            ->where('id_destino', $id_destino)
            ->where('estado', 'CREADO')
            ->whereIn('tipo', ['P', 'I'])
            ->select('id_pedido', 'id_persona', 'id_destino', 'codigo', 'total_monto', 'tipo') // Solo lo que usa el Pedido
            ->get();

        return $this->successResponse($pedidos);
    } */

    /*  public function showPedidoByIdPersona($id_persona)
    {
        $pedido = Pedido::with(['detalles.material', 'persona'])
            ->where('id_persona', $id_persona)
            ->where('estado', 'CREADO')
            ->first();

        return $this->successResponse($pedido ?: [], 'Pedido obtenido correctamente');
    } */


    public function pagoAbono(Request $request)
    {
        $request->validate([
            'id_pedido'      => 'required|exists:pedidos,id_pedido',
            'monto'          => 'required|numeric|min:0.1',
            'transaccion_id' => 'nullable|string|max:100',
            'metodo_pago'    => 'nullable|in:EFECTIVO,YAPE,PLIN,TRANSFERENCIA,TARJETA'
        ]);

        return DB::transaction(function () use ($request) {
            // Bloqueamos la fila para evitar que dos admins registren pagos al mismo tiempo
            $pedido = Pedido::lockForUpdate()->findOrFail($request->id_pedido);

            // --- VALIDACIONES ---
            if (in_array($pedido->estado, ['ANULADO', 'ENTREGADO'])) {
                return response()->json(['error' => "El pedido está {$pedido->estado}."], 422);
            }

            if ((float)$pedido->saldo_pendiente <= 0) {
                return response()->json(['error' => "El pedido ya está pagado totalmente."], 422);
            }

            if ((float)$request->monto > (float)$pedido->saldo_pendiente) {
                return response()->json([
                    'error' => "Monto excede el saldo. Saldo actual: S/ " . number_format($pedido->saldo_pendiente, 2)
                ], 422);
            }

            // --- LÓGICA DE TRANSACCIÓN ID DINÁMICO ---
            $transaccionId = $request->input('transaccion_id');

            if (empty($transaccionId)) {
                $anioActual = date('Y');
                $metodo = $request->input('metodo_pago', 'EFECTIVO');

                $prefijo = match ($metodo) {
                    'EFECTIVO'      => 'EFE',
                    'YAPE'          => 'YAP',
                    'PLIN'          => 'PLN',
                    'TRANSFERENCIA' => 'TRA',
                    'TARJETA'       => 'TAR',
                    default         => 'DEF',
                };

                // 1. Buscamos el último pago registrado en este año, ordenando por ID de forma descendente
                $ultimoPago = PedidoPago::whereYear('created_at', $anioActual)
                    ->orderBy('id_pago', 'desc') // Usamos el ID de la tabla que es autoincremental y único
                    ->first();

                // 2. Extraemos el número correlativo o empezamos en 1 si no hay registros
                if ($ultimoPago) {
                    // Extraemos los últimos 5 dígitos del string (ej: de "EFE-2026-00004" toma "00004")
                    $ultimoCorrelativo = (int) substr($ultimoPago->transaccion_id, -5);
                    $nuevoCorrelativo = $ultimoCorrelativo + 1;
                } else {
                    $nuevoCorrelativo = 1;
                }

                $transaccionId = "{$prefijo}-{$anioActual}-" . str_pad($nuevoCorrelativo, 5, '0', STR_PAD_LEFT);
            }

            // --- REGISTRO DEL PAGO ---
            PedidoPago::create([
                'id_pedido'      => $pedido->id_pedido,
                'monto'          => $request->monto,
                'transaccion_id' => $transaccionId,
                'metodo_pago'    => $request->input('metodo_pago', 'EFECTIVO'), // Dinámico
                'estado_visanet' => 'COMPLETADO',
                'fecha_pago'     => now(),
            ]);

            // --- ACTUALIZACIÓN DEL PEDIDO ---
            // Refrescamos la suma de pagos directamente de la DB
            $nuevoTotalPagado = (float)$pedido->pagos()->where('estado_visanet', 'COMPLETADO')->sum('monto');

            $pedido->estado = ($nuevoTotalPagado >= (float)$pedido->total_monto) ? 'PAGADO' : 'PENDIENTE';
            $pedido->save();

            return response()->json([
                'message' => 'Abono registrado con éxito',
                'data' => [
                    'transaccion_id' => $transaccionId,
                    'nuevo_estado'   => $pedido->estado,
                    'saldo_restante' => max(0, (float)$pedido->total_monto - $nuevoTotalPagado)
                ]
            ]);
        });
    }

    public function pagoPasarela(Request $request)
    {
        // 1. VALIDACIÓN ESTRUCTURAL BASE
        $request->validate([
            'id_pedido'      => 'required|exists:pedidos,id_pedido',
            'monto'          => 'required|numeric|min:0.1',
            'transaccion_id' => 'nullable|string|unique:pedido_pagos,transaccion_id',
            'estado_visanet' => 'required|in:AUTORIZADO,DENEGADO,PENDIENTE',
            'metodo_pago'    => 'required|in:VISA,MASTERCARD,PAGO_EFECTIVO',
            'raw_response'   => 'required|array'
        ]);

        // 2. VALIDACIÓN LÓGICA DINÁMICA (Cruzar Método con Contenido)
        $raw = $request->raw_response;

        if ($request->metodo_pago === 'PAGO_EFECTIVO') {
            if (!isset($raw['cip'])) {
                return response()->json([
                    'error' => 'Validación fallida: El método PAGO_EFECTIVO requiere un campo "cip" en el raw_response.'
                ], 422);
            }
        }

        if (in_array($request->metodo_pago, ['VISA', 'MASTERCARD'])) {
            // Validamos que exista 'tarjeta' o 'card' (según como lo mande tu pasarela)
            if (!isset($raw['tarjeta']) && !isset($raw['card'])) {
                return response()->json([
                    'error' => 'Validación fallida: Los pagos con tarjeta requieren información del plástico (tarjeta/card) en raw_response.'
                ], 422);
            }
        }

        // 3. PROCESAMIENTO DE TRANSACCIÓN
        return DB::transaction(function () use ($request) {
            // Bloqueo para evitar colisiones en Neon/PostgreSQL
            $pedido = Pedido::lockForUpdate()->findOrFail($request->id_pedido);

            // --- VALIDACIONES DE NEGOCIO ---
            if (in_array($pedido->estado, ['ANULADO', 'ENTREGADO'])) {
                return response()->json(['error' => "El pedido está {$pedido->estado}."], 422);
            }

            if ((float)$pedido->saldo_pendiente <= 0) {
                return response()->json(['error' => "El pedido ya está pagado totalmente."], 422);
            }

            // Validar exceso solo si es exitoso
            if ($request->estado_visanet === 'AUTORIZADO' && (float)$request->monto > (float)$pedido->saldo_pendiente) {
                return response()->json([
                    'error' => "Monto excede el saldo actual: S/ " . number_format($pedido->saldo_pendiente, 2)
                ], 422);
            }

            // --- GENERACIÓN DE ID DINÁMICO SEGÚN PREFIJO ---
            $transaccionId = $request->input('transaccion_id');

            if (empty($transaccionId)) {
                $anioActual = date('Y');

                $prefijo = match ($request->metodo_pago) {
                    'VISA'          => 'VIS',
                    'MASTERCARD'    => 'MAS',
                    'PAGO_EFECTIVO' => 'CIP',
                    default         => 'ONL',
                };

                // 1. Buscamos el último pago del año para obtener el número correlativo real
                $ultimoPago = PedidoPago::whereYear('created_at', $anioActual)
                    ->orderBy('id_pago', 'desc')
                    ->first();

                if ($ultimoPago) {
                    // Extraemos los últimos 5 dígitos del transaccion_id anterior
                    $ultimoCorrelativo = (int) substr($ultimoPago->transaccion_id, -5);
                    $nuevoCorrelativo = $ultimoCorrelativo + 1;
                } else {
                    $nuevoCorrelativo = 1;
                }

                $transaccionId = "{$prefijo}-{$anioActual}-" . str_pad($nuevoCorrelativo, 5, '0', STR_PAD_LEFT);
            }

            // --- REGISTRO DEL PAGO ---
            PedidoPago::create([
                'id_pedido'      => $pedido->id_pedido,
                'monto'          => $request->monto,
                'transaccion_id' => $transaccionId,
                'metodo_pago'    => $request->metodo_pago,
                'estado_visanet' => $request->estado_visanet,
                'raw_response'   => $request->raw_response,
                'fecha_pago'     => now(),
            ]);

            // --- ACTUALIZACIÓN DE SALDOS Y ESTADOS ---
            $mensaje = "Registro procesado.";

            if ($request->estado_visanet === 'AUTORIZADO') {
                // Solo sumamos dinero real confirmado
                $nuevoTotalPagado = (float)$pedido->pagos()
                    ->whereIn('estado_visanet', ['COMPLETADO', 'AUTORIZADO'])
                    ->sum('monto');

                $pedido->estado = ($nuevoTotalPagado >= (float)$pedido->total_monto) ? 'PAGADO' : 'PENDIENTE';
                $pedido->save();

                $mensaje = "Transacción autorizada y pedido actualizado.";
            }

            return response()->json([
                'estado_transaccion' => $request->estado_visanet,
                'transaccion_id'     => $transaccionId,
                'message'            => $mensaje,
                'pedido_estado'      => $pedido->estado,
                'resumen'            => [
                    'total_pagado'    => (float)$pedido->pagos()->whereIn('estado_visanet', ['COMPLETADO', 'AUTORIZADO'])->sum('monto'),
                    'saldo_restante'  => max(0, (float)$pedido->total_monto - (float)$pedido->pagos()->whereIn('estado_visanet', ['COMPLETADO', 'AUTORIZADO'])->sum('monto'))
                ]
            ]);
        });
    }

    public function pagoMasivo(Request $request)
    {
        // 1. Validaciones del Request
        $request->validate([
            'nro_operacion'       => 'required|string',
            'monto_total_voucher' => 'required|numeric|min:0',
            'comprobante_pago'    => 'required|mimes:jpg,jpeg,png,pdf|max:4096',
            'distribucion'        => 'required|array',
            'distribucion.*.id_pedido' => 'required|integer|exists:pedidos,id_pedido',
            'distribucion.*.monto'     => 'required|numeric|min:0.01',
        ]);

        // 2. Guardar archivo en disco (comprobante)
        $pathImagen = null;
        if ($request->hasFile('comprobante_pago')) {
            $pathImagen = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }

        try {
            return DB::transaction(function () use ($request, $pathImagen) {
                $sumaAbonosRepartidos = 0;
                $pagosGuardados = [];

                // Calculamos el total que se pretende distribuir en esta operación
                foreach ($request->distribucion as $item) {
                    $sumaAbonosRepartidos += (float)$item['monto'];
                }

                // --- VALIDACIÓN DE CUADRE DE CAJA ---
                $totalVoucher = round((float)$request->monto_total_voucher, 2);
                $totalRepartido = round($sumaAbonosRepartidos, 2);

                if ($totalRepartido !== $totalVoucher) {
                    $dif = round($totalVoucher - $totalRepartido, 2);
                    $msg = $totalRepartido > $totalVoucher
                        ? "La suma de abonos excede el voucher por S/ " . abs($dif)
                        : "Aún falta repartir S/ " . $dif . " del total del voucher.";
                    throw new Exception($msg);
                }

                foreach ($request->distribucion as $item) {
                    // Bloqueamos el pedido para evitar que otro proceso cambie el saldo al mismo tiempo
                    $pedido = Pedido::lockForUpdate()->findOrFail($item['id_pedido']);

                    // --- VALIDACIONES DE NEGOCIO ---

                    // A. No pagar pedidos que ya no deberían recibir abonos
                    if (in_array($pedido->estado, ['ANULADO', 'ENTREGADO'])) {
                        throw new Exception("El pedido #{$pedido->id_pedido} está {$pedido->estado} y no puede recibir pagos.");
                    }

                    // B. No pagar si ya no debe nada
                    if ((float)$pedido->saldo_pendiente <= 0) {
                        throw new Exception("El pedido #{$pedido->id_pedido} ya no tiene saldo pendiente.");
                    }

                    // C. No permitir que el abono sea mayor a la deuda actual del pedido
                    $montoAbono = (float)$item['monto'];
                    $saldoActual = (float)$pedido->saldo_pendiente;

                    if (round($montoAbono, 2) > round($saldoActual, 2)) {
                        throw new Exception(
                            "Error en Pedido #{$pedido->id_pedido}: Intentas abonar S/ " . number_format($montoAbono, 2) .
                                " pero solo debe S/ " . number_format($saldoActual, 2)
                        );
                    }

                    // --- 3. REGISTRO DEL PAGO ---
                    $pago = PedidoPago::create([
                        'id_pedido'        => $pedido->id_pedido,
                        'monto'            => $montoAbono,
                        'metodo_pago'      => 'TRANSFERENCIA',
                        'estado_visanet'   => 'COMPLETADO',
                        'comprobante_path' => $pathImagen,
                        'fecha_pago'       => now(),
                        'transaccion_id'   => "V-{$request->nro_operacion}-{$pedido->id_pedido}",
                        'raw_response'     => [
                            'nro_voucher_original' => $request->nro_operacion,
                            'monto_total_voucher'  => $totalVoucher,
                            'suma_repartida'       => $totalRepartido,
                            'proceso'              => 'PAGO_MASIVO_COMPROBANTE',
                            'ip_registro'          => $request->ip()
                        ]
                    ]);

                    // --- 4. ACTUALIZACIÓN DEL SALDO DEL PEDIDO ---
                    $nuevoSaldo = round($saldoActual - $montoAbono, 2);

                    $pedido->update([
                        'saldo_pendiente' => $nuevoSaldo,
                        // Si el saldo llega a 0 es PAGADO, de lo contrario se queda como PENDIENTE
                        'estado'          => ($nuevoSaldo <= 0) ? 'PAGADO' : 'PENDIENTE'
                    ]);

                    $pagosGuardados[] = $pago;
                }

                return response()->json([
                    'status' => 'success',
                    'message' => "Se registraron " . count($pagosGuardados) . " abonos exitosamente.",
                    'data' => [
                        'operacion' => $request->nro_operacion,
                        'total_distribuido' => $totalRepartido
                    ]
                ], 201);
            });
        } catch (Exception $e) {
            // El DB::transaction hace rollback automático de todo si entra aquí
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function marcarComoEntregado($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Validación de negocio: Solo se entrega lo que está pagado
        if ($pedido->estado !== 'PAGADO') {
            return response()->json([
                'error' => 'El pedido debe estar en estado PAGADO para poder entregarse físicamente.'
            ], 422);
        }

        $pedido->update([
            'estado' => 'ENTREGADO',
            'updated_at' => now() // Usamos esto como marca de tiempo de entrega
        ]);

        return response()->json(['message' => 'Materiales entregados con éxito.']);
    }

    public function anularPedido($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Un pedido ENTREGADO no debería anularse sin un proceso de devolución previo
        if ($pedido->estado === 'ENTREGADO') {
            return response()->json(['error' => 'No se puede anular un pedido que ya fue entregado.'], 422);
        }

        // Si tiene pagos registrados, podrías decidir si los borras o los dejas como historial
        DB::transaction(function () use ($pedido) {
            $pedido->update(['estado' => 'ANULADO']);
            // Opcional: Podrías marcar los pagos como anulados también si tuvieras esa columna
        });

        return response()->json(['message' => 'El pedido ha sido ANULADO. El cupo para este periodo ha sido liberado.']);
    }

    public function showPedidoByCodigo($codigo)
    {
        $pedido = Pedido::with(['detalles.material', 'persona', 'pagos'])
            ->where('codigo', $codigo)
            ->first();

        if ($pedido) {
            // Calculamos el saldo al vuelo para la respuesta JSON
            $totalAbonado = $pedido->pagos->sum('monto');
            $pedido->saldo_pendiente = $pedido->total_monto - $totalAbonado;
        }

        return response()->json($pedido ?? []);
    }

    /* public function estadoPorCodigo($codigo)
    {
        $pedido = Pedido::where('codigo', $codigo)->firstOrFail();
        return response()->json(['estado' => $pedido->estado]);
    } */
}
