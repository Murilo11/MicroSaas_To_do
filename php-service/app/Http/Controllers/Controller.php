<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'MicroSaas To-Do API',
    version: '1.0.0',
    description: 'API de gerenciamento de quadros e cartões Kanban'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Servidor local'
)]
abstract class Controller
{
    //
}
