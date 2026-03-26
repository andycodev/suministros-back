<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\Persona;
use App\Models\Suministros\SPersonaUbicacion;

class PersonaController extends Controller
{

    public function searchPersona(Request $request)
    {
        $validated = $request->validate([
            'id_union'    => 'required|integer',
            'id_campo'    => 'required|integer',
            'id_distrito' => 'required|integer',
            'id_iglesia'  => 'required|integer',
            'documento'   => 'required|string|min:3|max:15|regex:/^[a-zA-Z0-9]+$/',
        ]);

        $query = Persona::query();

        $query->join('iglesia_iglesias as ii', 'ii.id_iglesia', '=', 'personas.id_iglesia')
            ->join('iglesia_distritos as idis', 'idis.id_distrito', '=', 'ii.id_distrito')
            ->join('iglesia_campos as ic', 'ic.id_campo', '=', 'ii.id_campo')
            ->join('iglesia_unions as iu', 'iu.id_union', '=', 'ii.id_union');

        $query->select('personas.*');

        $query->where('personas.documento', 'LIKE', '%' . $validated['documento'] . '%');

        $query->where([
            ['iu.id_union',      $validated['id_union']],
            ['ic.id_campo',      $validated['id_campo']],
            ['idis.id_distrito', $validated['id_distrito']],
            ['ii.id_iglesia',    $validated['id_iglesia']],
        ]);

        $query->with(['iglesia']);

        $personas = $query->limit(30)->get();

        $mensaje = $personas->isNotEmpty() ? 'Personas encontradas' : 'No se encontraron resultados';

        return $this->successResponse($personas, $mensaje);
    }

    public function getPersonaById($id_persona)
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
            'id_union'   => $iglesia->union->id_union,
            'union'    => $iglesia->union->nombre ?? 'N/A',
            'id_campo' => $iglesia->campo->id_campo,
            'campo'    => $iglesia->campo->nombre ?? 'N/A',
            'id_distrito' => $iglesia->distrito->id_distrito,
            'distrito' => $iglesia->distrito->nombre ?? 'N/A',
            'id_iglesia' => $iglesia->id_iglesia,
            'iglesia'  => $iglesia->nombre,
        ]);
    }
}
