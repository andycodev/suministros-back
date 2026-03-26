<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\Persona;
use App\Models\User;

class AuthController extends Controller
{
    public function register()
    {
        $validated = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'user' => $user
        ], 201);
    }

    public function login_2() {}

    public function logout() {}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $persona = Persona::with('iglesia')
            ->where('email', $request->email)
            ->where('documento', $request->password)
            ->first();

        // return response()->json($persona);

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
    }

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
