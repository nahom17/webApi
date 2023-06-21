<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

use App\Models\TodoModel;

// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

// Retrieve the request body
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Check if the required fields are present in the request body
if (!isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing required fields']);
    exit;
}

// Create a new task
$todoModel = new TodoModel();
$todoModel->create($data['name']);

// Return a success response
http_response_code(201);
echo json_encode(['message' => 'Task created successfully']);
