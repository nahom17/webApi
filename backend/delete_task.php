<?php
header('Access-Control-Allow-Origin: https://projects.local');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

use App\Models\TodoModel;

// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

// Retrieve the task ID from the query parameters
$taskId = $_GET['id'] ?? null;

// Check if the task ID is provided
if (!$taskId) {
    http_response_code(400);
    echo json_encode(['message' => 'Task ID is required']);
    exit;
}

// Delete the task
$todoModel = new TodoModel();
$todoModel->destroy($taskId);

// Return a success response
http_response_code(200);
echo json_encode(['message' => 'Task deleted successfully']);

// Compare this snippet from frontend\app.js: