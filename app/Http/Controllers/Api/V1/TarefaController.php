<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TarefaResource;
use App\Models\Tarefa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return TarefaResource::collection(Tarefa::query()->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'titulo' => ['required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string'],
            'concluida' => ['sometimes', 'boolean'],
        ]);

        return (new TarefaResource(Tarefa::query()->create($dados)))
            ->additional(['message' => 'Tarefa criada com sucesso.'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa): TarefaResource
    {
        return new TarefaResource($tarefa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa): TarefaResource
    {
        $dados = $request->validate([
            'titulo' => ['sometimes', 'required', 'string', 'max:120'],
            'descricao' => ['sometimes', 'nullable', 'string'],
            'concluida' => ['sometimes', 'boolean'],
        ]);

        $tarefa->update($dados);

        return new TarefaResource($tarefa->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa): Response
    {
        $tarefa->delete();

        return response()->noContent();
    }
}
