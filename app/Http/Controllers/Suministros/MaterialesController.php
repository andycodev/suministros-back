<?php

namespace App\Http\Controllers\Suministros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suministros\SMaterial;

class MaterialesController extends Controller
{
    public function getMaterialesPersonas()
    {
        $materiales = SMaterial::where('activo', true)
            ->where('tipo', 'P')
            ->orderBy('precio', 'asc')
            ->get();

        return response()->json($materiales);
    }

    public function getMaterialesIglesias()
    {
        $materiales = SMaterial::where('activo', true)
            ->where('tipo', 'I')
            ->orderBy('precio', 'asc')
            ->get();

        return response()->json($materiales);
    }

    public function store(Request $request)
    {
        $material = SMaterial::create($request->all());
        return response()->json($material, 201);
    }

    public function update(Request $request, $id)
    {
        $material = SMaterial::findOrFail($id);
        $material->update($request->all());
        return response()->json($material);
    }

    public function destroy($id)
    {
        $material = SMaterial::findOrFail($id);
        $material->delete();
        return response()->json(['message' => 'Material eliminado']);
    }
}
