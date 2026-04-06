<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 1. Errores de Validación (422)
        /*    $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'data'    => $e->errors(),
            ], 422);
        }); */
        // 1. Errores de Validación (422)
        $exceptions->render(function (ValidationException $e) {
            // Obtenemos todos los errores
            $errors = $e->errors();

            // Extraemos el primer mensaje de error que aparezca
            $firstError = collect($errors)->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstError, // <--- Ahora dirá "Ya existe un pedido..."
                'data'    => $errors,
            ], 422);
        });

        // 2. Errores de "No encontrado" (404)
        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso no existe',
                'data'    => null,
            ], 404);
        });

        // 3. Cualquier otro error inesperado (500)
        $exceptions->render(function (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'data'    => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        });
    })->create();
