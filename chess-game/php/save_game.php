<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['gameState'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid game state']);
        exit;
    }

    $gameState = $data['gameState'];
    $filename = 'saved_games/' . time() . '.json';
    
    if (file_put_contents($filename, json_encode($gameState))) {
        echo json_encode(['success' => true, 'message' => 'Game saved successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save game']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?> 