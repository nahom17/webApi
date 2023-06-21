<?php


use App\Http\Controllers\TodoController;
use App\Http\Controllers\CompleteController;
use App\Http\Controllers\OpenController;

return [
    'GET' => [
        // resource => [Class, 'method']
        '/' => [TodoController::class, 'index'],
        'task' => [TodoController::class, 'show'], // Requires ID as parameter in URI
        'completed' => [CompleteController::class, 'show'], // Requires ID as parameter in URI
        'open' => [OpenController::class, 'show'], // Requires ID as parameter in URI
    ],
    // Create
    'POST' => [
        'task' => [TodoController::class, 'create'],
        'completed' => [CompleteController::class, 'create'],
        'open' => [OpenController::class, 'create'],
    ],
    // Update
    'PATCH' => [
        'task' => [TodoController::class, 'update'], // Requires ID as parameter in URI
        'completed' => [CompleteController::class, 'update'], // Requires ID as parameter in URI
        'open' => [OpenController::class, 'update'], // Requires ID as parameter in URI
    ],
    // Update
    'PUT' => [],
    // Delete
    'DELETE' => [
        'task' => [TodoController::class, 'destroy'], // Requires ID as parameter in URI
        'completed' => [CompleteController::class, 'update'], // Requires ID as parameter in URI
        'open' => [OpenController::class, 'update'], // Requires ID as parameter in URI
    ]
];

