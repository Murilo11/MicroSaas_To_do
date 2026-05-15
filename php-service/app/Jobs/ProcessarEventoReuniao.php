<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessarEventoReuniao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $payload = $this->data;

        $reuniaoExistente = \DB::table('reunioes_read')->where('id', $payload['id'])->exists();

        if ($reuniaoExistente){
            Log::warning("Reunião {$payload['id']} já precessada. Ignorando ...");
            return;
        }

        \DB::table('reunioes_read')->insert([
            'id' => $payload['id'],
            'titulo' => $payload['titulo'],
            'data_reuniao' => $payload['data_reuniao'],
            'organizador_nome' => $payload['organizador_nome'],
            'created_at' => now(),
            'updated_at' =>now(),
        ]);

        Cache::forget("reuniao:item:{$payload['id']}");
        Cache::forget("boards:all"); 

        log::info("Reunião {$payload['id']} salvo no banco e cache invalidado com sucesso!");
    }
}