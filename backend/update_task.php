<?php
header('Access-Control-Allow-Origin: https://projects.local');
header('Access-Control-Allow-Methods: PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

use App\Models\TodoModel;

// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

// Retrieve the request body
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Check if the required fields are present in the request body
if (!isset($data['id']) || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing required fields']);
    exit;
}

// Update the task
$todoModel = new TodoModel();
$todoModel->update($data['id'], $data['name']);

// Return a success response
http_response_code(200);
echo json_encode(['message' => 'Task updated successfully']);
