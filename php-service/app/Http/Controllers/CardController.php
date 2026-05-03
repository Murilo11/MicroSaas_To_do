<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CardController extends Controller
{
    public function __construct(
        protected CardRepositoryInterface $cardRepository
    ) {}

    #[OA\Get(
        path: '/api/cards',
        summary: 'Lista todos os cartões',
        tags: ['Cards'],
        responses: [
            new OA\Response(response: 200, description: 'Sucesso')
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json($this->cardRepository->getAll());
    }

    #[OA\Post(
        path: '/api/cards',
        summary: 'Cria um novo cartão',
        tags: ['Cards'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['board_id', 'title'],
                properties: [
                    new OA\Property(property: 'board_id', type: 'integer'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                    new OA\Property(property: 'position', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Criado'),
            new OA\Response(response: 422, description: 'Dados inválidos')
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'board_id'    => 'required|integer|exists:boards,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'position'    => 'nullable|integer|min:0',
        ]);
        return response()->json($this->cardRepository->create($validated), 201);
    }

    #[OA\Get(
        path: '/api/cards/{id}',
        summary: 'Exibe um cartão',
        tags: ['Cards'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Sucesso'),
            new OA\Response(response: 404, description: 'Não encontrado')
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return response()->json($this->cardRepository->findById($id));
    }

    #[OA\Put(
        path: '/api/cards/{id}',
        summary: 'Atualiza um cartão',
        tags: ['Cards'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'board_id', type: 'integer'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                    new OA\Property(property: 'position', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Atualizado'),
            new OA\Response(response: 404, description: 'Não encontrado')
        ]
    )]
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'board_id'    => 'sometimes|integer|exists:boards,id',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'position'    => 'nullable|integer|min:0',
        ]);
        return response()->json($this->cardRepository->update($id, $validated));
    }

    #[OA\Delete(
        path: '/api/cards/{id}',
        summary: 'Remove um cartão',
        tags: ['Cards'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 204, description: 'Removido'),
            new OA\Response(response: 404, description: 'Não encontrado')
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->cardRepository->delete($id);
        return response()->json(null, 204);
    }
}
