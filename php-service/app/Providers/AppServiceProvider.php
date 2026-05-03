<?php

namespace App\Providers;

use App\Repositories\Contracts\BoardRepositoryInterface;
use App\Repositories\Contracts\CardRepositoryInterface;
use App\Repositories\Eloquent\EloquentBoardRepository;
use App\Repositories\Eloquent\EloquentCardRepository;
use Illuminate\Support\ServiceProvider;

/**
 * @OA\Info(
 *     title="MicroSaas To-Do API",
 *     version="1.0.0",
 *     description="API de gerenciamento de quadros e cartões Kanban"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local"
 * )
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BoardRepositoryInterface::class, EloquentBoardRepository::class);
        $this->app->bind(CardRepositoryInterface::class, EloquentCardRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
