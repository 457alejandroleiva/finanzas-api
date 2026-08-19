<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterEgresoRequest;
use App\Http\Requests\StoreEgresoRequest;
use App\Http\Requests\UpdateEgresoRequest;
use App\Models\Egreso;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EgresoController extends Controller
{
    public function index(FilterEgresoRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $filters = $request->validated();

        $egresos = $this->egresosDelUsuario($userId)
            ->when(isset($filters['anio']), fn (Builder $query) => $query->whereYear('fecha', $filters['anio']))
            ->when(isset($filters['mes']), fn (Builder $query) => $query->whereMonth('fecha', $filters['mes']))
            ->with($this->relacionesDelUsuario($userId))
            ->orderByDesc('fecha')
            ->get();

        return response()->json($egresos);
    }

    public function store(StoreEgresoRequest $request): JsonResponse
    {
        $egreso = $request->user()->egresos()->create($request->validated());

        $egreso->load($this->relacionesDelUsuario($request->user()->id));

        return response()->json($egreso, Response::HTTP_CREATED);
    }

    public function show(Request $request, int $egreso): JsonResponse
    {
        $egreso = $this->egresosDelUsuario($request->user()->id)
            ->with($this->relacionesDelUsuario($request->user()->id))
            ->findOrFail($egreso);

        return response()->json($egreso);
    }

    public function update(UpdateEgresoRequest $request, int $egreso): JsonResponse
    {
        $egreso = $this->egresosDelUsuario($request->user()->id)->findOrFail($egreso);

        $egreso->update($request->validated());
        $egreso->load($this->relacionesDelUsuario($request->user()->id));

        return response()->json($egreso);
    }

    public function destroy(Request $request, int $egreso): Response
    {
        $egreso = $this->egresosDelUsuario($request->user()->id)->findOrFail($egreso);

        $egreso->delete();

        return response()->noContent();
    }

    private function egresosDelUsuario(int $userId): Builder
    {
        return Egreso::query()->where('user_id', $userId);
    }

    private function relacionesDelUsuario(int $userId): array
    {
        return [
            'categoria' => fn (Builder $query) => $query
                ->where('user_id', $userId)
                ->where('tipo', 'egreso'),
            'subcategoria' => fn (Builder $query) => $query
                ->whereHas('categoria', fn (Builder $categoria) => $categoria
                    ->where('user_id', $userId)
                    ->where('tipo', 'egreso')),
        ];
    }
}
