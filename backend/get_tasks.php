<?php

namespace App\Models;

// Fetch tasks from the database
$tasks = TodoModel::all();

// Return tasks as JSON response
header('Content-Type: application/json');
echo json_encode($tasks);
