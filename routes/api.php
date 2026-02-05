<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Setup\SetupController;
use App\Http\Controllers\Suministros\PersonasController;
use App\Http\Controllers\Suministros\MaterialesController;
use App\Http\Controllers\Suministros\PedidosController;
use App\Http\Controllers\Report\ReportController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('setup')->group(function () {
    Route::get('/iglesia-unions', [SetupController::class, 'getIglesiaUnions']);
    Route::get('/iglesia-regions', [SetupController::class, 'getIglesiaRegions']);
    Route::get('/iglesia-campos', [SetupController::class, 'getIglesiaCampos']);
    Route::get('/iglesia-distritos', [SetupController::class, 'getIglesiaDistritos']);
    Route::get('/iglesia-iglesias', [SetupController::class, 'getIglesiaIglesias']);

    Route::get('/iglesia-campos-by-union/{id_union}', [SetupController::class, 'getIglesiaCamposByUnion']);
    Route::get('/iglesia-distritos-by-campo/{id_campo}', [SetupController::class, 'getIglesiaDistritosByCampo']);
    Route::get('/iglesia-iglesias-by-distrito/{id_distrito}', [SetupController::class, 'getIglesiaIglesiasByDistrito']);
});

Route::prefix('suministros')->group(function () {

    Route::get('/personas/buscar', [PersonasController::class, 'searchPersona']);
    Route::get('/personas/{id_persona}', [PersonasController::class, 'getPersonaById']);

    Route::get('/materiales-personas', [MaterialesController::class, 'getMaterialesPersonas']);
    Route::get('/materiales-iglesias', [MaterialesController::class, 'getMaterialesIglesias']);

    Route::post('/pedidos', [PedidosController::class, 'store']);
    Route::post('/pedidos/{id}/detalles', [PedidosController::class, 'agregarDetalles']);
    Route::get('/pedidos/{id_pedido}', [PedidosController::class, 'showPedidoByIdPedido']);
    Route::get('/pedidos/persona/{id_persona}', [PedidosController::class, 'showPedidoByIdPersona']);
    Route::get('/pedidos/destino/{id_destino}', [PedidosController::class, 'showPedidoByIdDestino']);
    Route::get('/pedidos/codigo/{codigo}', [PedidosController::class, 'showPedidoByCodigo']);

    /*    Route::post('/pedidos/{id}/pagar', [PedidosController::class, 'pagar']);
    Route::post('/pedidos/{id}/confirmar-pago', [PedidosController::class, 'confirmarPago']);
    Route::get('/pedido/consulta/{codigo}', [PedidosController::class, 'buscarPorCodigo']); */
});

Route::prefix('reportes')->group(function () {

    Route::get('/mis-pedidos', [ReportController::class, 'getMisPedidos']);
});
