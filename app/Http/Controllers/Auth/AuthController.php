<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\Persona;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        // 1. Extraer el documento para la verificación previa
        $documento = request('documento');

        // 2. Lógica de "Negocio": ¿La persona ya tiene cuenta?
        // Buscamos a la persona por documento
        $personaExiste = Persona::where('documento', $documento)->first();

        if ($personaExiste) {
            // Verificamos si esta persona ya está amarrada a un registro en la tabla users
            $usuarioVinculado = User::where('id_persona', $personaExiste->id_persona)->first();

            if ($usuarioVinculado) {
                return $this->errorResponse('Esta persona ya tiene una cuenta de usuario asignada.');
            }
        }

        // 3. Validación de Laravel (Si llega aquí, es porque el DNI no tiene usuario aún)
        $validated = request()->validate([
            'nombres'    => 'required|string',
            'ap_paterno' => 'required|string',
            'ap_materno' => 'required|string',
            'documento'  => 'required|string',
            'id_iglesia' => 'required|exists:iglesia_iglesias,id_iglesia', // Validamos que la iglesia exista en tu tabla
            'name'       => 'required|string',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6'
        ]);

        // 4. Proceso de Guardado con Transacción
        return DB::transaction(function () use ($validated, $personaExiste) {

            // Usamos updateOrCreate para actualizar los datos si la persona existía (pero no tenía usuario)
            // o crearla desde cero si no existía.
            $persona = Persona::updateOrCreate(
                ['documento' => $validated['documento']],
                [
                    'nombres'    => $validated['nombres'],
                    'ap_paterno' => $validated['ap_paterno'],
                    'ap_materno' => $validated['ap_materno'],
                    'email'      => $validated['email'], // Sincronizamos el correo
                    'id_iglesia' => $validated['id_iglesia'],
                ]
            );

            // Crear el Usuario
            $user = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'password'   => bcrypt($validated['password']),
                'id_persona' => $persona->id_persona,
                'activo'     => true
            ]);

            // Generar Token de Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'user'  => $user->load('persona'),
                'token' => $token
            ], 'Usuario registrado exitosamente');
        });
    }

    public function login()
    {
        $validated = request()->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::with(['persona.iglesia'])
            ->where('email', $validated['email'])
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->errorResponse('Credenciales incorrectas.');
        }

        if (!$user->activo) {
            return $this->errorResponse('Tu cuenta está desactivada.');
        }

        if (!$user->is_director) {
            return $this->errorResponse('Acceso denegado: Solo los directores autorizados pueden ingresar a este módulo.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id_user'     => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'is_director' => $user->is_director,
                'id_persona'  => $user->persona->id_persona,
                'full_name'   => "{$user->persona->nombres} {$user->persona->ap_paterno} {$user->persona->ap_materno}",
                'documento'   => $user->persona->documento,
                'iglesia'     => $user->persona->iglesia->nombre ?? 'N/A',
                'id_iglesia'  => $user->persona->id_iglesia,
            ]
        ], 'Bienvenido Director, ' . $user->persona->nombres);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return $this->successResponse('Sesión cerrada correctamente');
    }

    /*    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $persona = Persona::with('iglesia')
            ->where('email', $request->email)
            ->where('documento', $request->password)
            ->first();

        if ($persona) {
            return response()->json([
                'success' => true,
                'message' => 'Bienvenido ' . $persona->nombres . ' ' . $persona->ap_paterno . ' ' . $persona->ap_materno,
                'id_persona' => $persona->id_persona,
                'email' => $persona->email,
                'documento' => $persona->documento,
                'nombre' => $persona->nombres . ' ' . $persona->ap_paterno . ' ' . $persona->ap_materno,
                'iglesia' => $persona->iglesia->nombre ?? 'N/A',
                'id_iglesia' => $persona->iglesia->id_iglesia ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas.'
        ], 401);
    } */

    public function showPersonaById($id_persona)
    {
        $persona = Persona::with([
            'iglesia',
            'iglesia.union',
            'iglesia.campo',
            'iglesia.distrito'
        ])
            ->findOrFail($id_persona);

        $iglesia = $persona->iglesia;
        if (!$iglesia) {
            return response()->json([
                'message' => 'La persona existe, pero no tiene una iglesia asignada en el sistema.'
            ], 404);
        }

        return response()->json([
            'id_persona' => $persona->id_persona,
            'persona'    => "{$persona->nombres} {$persona->ap_paterno} {$persona->ap_materno}",
            'union'    => $iglesia->union->nombre ?? 'N/A',
            'campo'    => $iglesia->campo->nombre ?? 'N/A',
            'distrito' => $iglesia->distrito->nombre ?? 'N/A',
            'iglesia'  => $iglesia->nombre,
        ]);
    }
}
