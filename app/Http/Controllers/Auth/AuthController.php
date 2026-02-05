<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\SPersona;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $persona = SPersona::with('iglesia')
            ->where('email', $request->email)
            ->where('documento', $request->password)
            ->first();

        // return response()->json($persona);

        if ($persona) {
            return response()->json([
                'id_persona' => $persona->id_persona,
                'email' => $persona->email,
                'documento' => $persona->documento,
                'nombre' => $persona->nombres . ' ' . $persona->ap_paterno . ' ' . $persona->ap_materno,
                'iglesia' => $persona->iglesia->nombre ?? 'N/A',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas.'
        ], 401);
    }

    public function showPersonaById($id_persona)
    {
        $persona = SPersona::with([
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
