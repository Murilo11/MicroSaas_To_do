<?php

namespace App\Providers;

use App\Models\Board;
use App\Models\Card;
use App\Repositories\Contracts\BoardRepositoryInterface;
use App\Repositories\Contracts\CardRepositoryInterface;
use App\Repositories\Eloquent\EloquentBoardRepository;
use App\Repositories\Eloquent\EloquentCardRepository;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Prometheus\Facades\Prometheus;

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
        $this->app->bind(
            CardRepositoryInterface::class,
            EloquentCardRepository::class
        );

        $this->app->bind(
            BoardRepositoryInterface::class,
            EloquentBoardRepository::class
        );
    }

    public function boot(): void
    {
        Health::checks([
            DatabaseCheck::new(),
            RedisCheck::new(),
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),
        ]);

        // Registrar métricas do Prometheus
        Prometheus::addGauge('Total de Boards ativos')
            ->name('boards_active_count')
            ->helpText('Total de Boards ativos no banco de dados')
            ->value(fn () => Board::count());

        Prometheus::addGauge('Total de Cards ativos')
            ->name('cards_active_count')
            ->helpText('Total de Cards ativos no banco de dados')
            ->value(fn () => Card::count());

    }
}
