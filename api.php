<?php
require_once 'MessageController.php';

$request = $_GET['request'] ?? '';

switch ($request) {
    case 'list':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            MessageController::list();
        }
        break;
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            MessageController::store();
        }
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
}
