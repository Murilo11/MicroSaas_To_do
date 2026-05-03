<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\BoardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BoardController extends Controller
{
    public function __construct(
        protected BoardRepositoryInterface $boardRepository
    ) {}

    #[OA\Get(
        path: '/api/boards',
        summary: 'Lista todos os quadros',
        tags: ['Boards'],
        responses: [
            new OA\Response(response: 200, description: 'Sucesso')
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json($this->boardRepository->getAll());
    }

    #[OA\Post(
        path: '/api/boards',
        summary: 'Cria um novo quadro',
        tags: ['Boards'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [new OA\Property(property: 'title', type: 'string')]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Criado'),
            new OA\Response(response: 422, description: 'Dados inválidos')
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['title' => 'required|string|max:255']);
        return response()->json($this->boardRepository->create($validated), 201);
    }

    #[OA\Get(
        path: '/api/boards/{id}',
        summary: 'Exibe um quadro',
        tags: ['Boards'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Sucesso'),
            new OA\Response(response: 404, description: 'Não encontrado')
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return response()->json($this->boardRepository->findById($id));
    }

    #[OA\Put(
        path: '/api/boards/{id}',
        summary: 'Atualiza um quadro',
        tags: ['Boards'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'title', type: 'string')]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Atualizado'),
            new OA\Response(response: 404, description: 'Não encontrado')
        ]
    )]
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate(['title' => 'required|string|max:255']);
        return response()->json($this->boardRepository->update($id, $validated));
    }

    #[OA\Delete(
        path: '/api/boards/{id}',
        summary: 'Remove um quadro',
        tags: ['Boards'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 204, description: 'Removido'),
            new OA\Response(response: 404, description: 'Não encontrado')
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->boardRepository->delete($id);
        return response()->json(null, 204);
    }
}
