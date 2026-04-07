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
        $v = $request->validate([
            'id_union'    => 'required|integer',
            'id_campo'    => 'required|integer',
            'id_distrito' => 'required|integer',
            'id_iglesia'  => 'required|integer',
            'documento'   => 'required|string|min:3',
        ]);

        // Empezamos la consulta desde Persona
        $personas = Persona::where('documento', 'LIKE', $v['documento'] . '%') // Quitamos el % inicial para usar índice
            ->where('id_iglesia', $v['id_iglesia']) // Filtro directo (más rápido)
            ->whereHas('iglesia.distrito.campo.union', function ($query) use ($v) {
                // Estos filtros aseguran que la iglesia pertenezca a la jerarquía seleccionada
                $query->where('iglesia_distritos.id_distrito', $v['id_distrito'])
                    ->where('iglesia_campos.id_campo', $v['id_campo'])
                    ->where('iglesia_unions.id_union', $v['id_union']);
            })
            ->with(['iglesia.distrito.campo.union']) // Carga la jerarquía para mostrarla en el UI
            ->limit(20)
            ->get();

        return $this->successResponse($personas);
    }

    /* public function searchPersona(Request $request)
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
    } */

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
            return $this->errorResponse('La persona existe, pero no tiene una iglesia asignada en el sistema.');
        }

        return $this->successResponse([
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
