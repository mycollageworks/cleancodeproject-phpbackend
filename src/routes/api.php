<?php

use App\Controllers\NoteController;

// Example: Using the Bramus Router (composer require bramus/router)
use Bramus\Router\Router;


$router->get('/notes', [NoteController::class, 'index']);
$router->post('/notes', [NoteController::class, 'store']);
$router->get('/notes/{id}', [NoteController::class, 'show']);
$router->put('/notes/{id}', [NoteController::class, 'update']);
$router->delete('/notes/{id}', [NoteController::class, 'destroy']);
