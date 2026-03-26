<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\Material;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function getMaterialesByTipo(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'nullable|in:P,I',
        ]);

        $materiales = Material::where('activo', true)
            ->when($validated['tipo'] ?? null, function ($query, $tipo) {
                return $query->where('tipo', Str::upper($tipo));
            })
            ->orderBy('precio', 'asc')
            ->get();

        return $this->successResponse($materiales);
    }

    public function getMaterialesPersonas()
    {
        $materiales = Material::where('activo', true)
            ->where('tipo', 'P')
            ->orderBy('precio', 'asc')
            ->get();

        return $this->successResponse($materiales, 'Materiales obtenidos correctamente');
    }

    public function getMaterialesIglesias()
    {
        $materiales = Material::where('activo', true)
            ->where('tipo', 'I')
            ->orderBy('precio', 'asc')
            ->get();

        return $this->successResponse($materiales, 'Materiales obtenidos correctamente');
    }

    /*  public function store(Request $request)
    {
        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        return response()->json($material);
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();
        return response()->json(['message' => 'Material eliminado']);
    } */
}
