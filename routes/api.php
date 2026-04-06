<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Setup\SetupController;
use App\Http\Controllers\Suministros\PersonaController;
use App\Http\Controllers\Suministros\MaterialController;
use App\Http\Controllers\Suministros\PedidoController;
use App\Http\Controllers\Suministros\PeriodoController;
use App\Http\Controllers\Report\ReportController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin Token)
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// El Setup suele ser público para llenar los combos del registro
Route::prefix('setup')->group(function () {
    Route::get('/periodos', [PeriodoController::class, 'getPeriodos']);
    Route::get('/iglesia-unions', [SetupController::class, 'getIglesiaUnions']);
    Route::get('/iglesia-regions', [SetupController::class, 'getIglesiaRegions']);
    Route::get('/iglesia-campos', [SetupController::class, 'getIglesiaCampos']);
    Route::get('/iglesia-distritos', [SetupController::class, 'getIglesiaDistritos']);
    Route::get('/iglesia-iglesias', [SetupController::class, 'getIglesiaIglesias']);
    Route::get('/iglesia-campos-by-union/{id_union}', [SetupController::class, 'getIglesiaCamposByUnion']);
    Route::get('/iglesia-distritos-by-campo/{id_campo}', [SetupController::class, 'getIglesiaDistritosByCampo']);
    Route::get('/iglesia-iglesias-by-distrito/{id_distrito}', [SetupController::class, 'getIglesiaIglesiasByDistrito']);
});


/* |--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Sin Sanctum)
|--------------------------------------------------------------------------
*/
Route::prefix('suministros')->group(function () {
    // Sacamos estas para que el frontend pueda usarlas antes del login
    Route::get('/personas/buscar', [PersonaController::class, 'searchPersona']);
    Route::get('/personas/{id_persona}', [PersonaController::class, 'getPersonaById']);
    Route::get('/materiales', [MaterialController::class, 'getMaterialesByTipo']);
    Route::get('/materiales-personas', [MaterialController::class, 'getMaterialesPersonas']);
    Route::get('/materiales-iglesias', [MaterialController::class, 'getMaterialesIglesias']);

    Route::post('/pedidos/{id}/detalles', [PedidoController::class, 'agregarDetalles']);
    Route::get('/pedidos/destino/{id_destino}', [PedidoController::class, 'showPedidoByIdDestino']);
    Route::get('/pedidos/{id_pedido}', [PedidoController::class, 'showPedidoByIdPedido']);
    Route::post('/pedidos', [PedidoController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth - Logout (Solo si estás logueado puedes salir)
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Suministros
    Route::prefix('suministros')->group(function () {
        // Route::get('/periodos', [PeriodoController::class, 'getPeriodos']);
        //Route::get('/personas/buscar', [PersonaController::class, 'searchPersona']);
        //Route::get('/personas/{id_persona}', [PersonaController::class, 'getPersonaById']);

        //Route::get('/materiales', [MaterialController::class, 'getMaterialesByTipo']);
        //Route::get('/materiales-personas', [MaterialController::class, 'getMaterialesPersonas']);
        //Route::get('/materiales-iglesias', [MaterialController::class, 'getMaterialesIglesias']);

        // Pedidos
        // Route::post('/pedidos', [PedidoController::class, 'store']);
        Route::put('/pedidos/{id}', [PedidoController::class, 'update']);
        Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy']);
        Route::post('/pedidos/{id}/detalles', [PedidoController::class, 'agregarDetalles']);
        // Route::get('/pedidos/{id_pedido}', [PedidoController::class, 'showPedidoByIdPedido']);
        Route::get('/pedidos/persona/{id_persona}', [PedidoController::class, 'showPedidoByIdPersona']);
        // Route::get('/pedidos/destino/{id_destino}', [PedidoController::class, 'showPedidoByIdDestino']);    
        Route::get('/pedidos/codigo/{codigo}', [PedidoController::class, 'showPedidoByCodigo']);

        // Acciones de Pago y Entrega
        Route::post('pedidos/pago-abono', [PedidoController::class, 'pagoAbono']);
        Route::post('pedidos/pago-pasarela', [PedidoController::class, 'pagoPasarela']);
        Route::post('pedidos/pago-masivo', [PedidoController::class, 'pagoMasivo']);
        Route::patch('pedidos/{id}/entregar', [PedidoController::class, 'marcarComoEntregado']);
        Route::patch('pedidos/{id}/anular', [PedidoController::class, 'anularPedido']);
    });

    // Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('/mis-pedidos', [ReportController::class, 'getMisPedidos']);
        Route::get('/mis-pedidos-pagos', [ReportController::class, 'getMisPedidosPagos']);
    });
});
