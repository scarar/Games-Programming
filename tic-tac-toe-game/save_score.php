<?php
session_start();

if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = [
        'X' => 0,
        'O' => 0
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['winner'])) {
        $_SESSION['scores'][$data['winner']]++;
        
        echo json_encode([
            'success' => true,
            'scores' => $_SESSION['scores']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request'
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => true,
        'scores' => $_SESSION['scores']
    ]);
}
?> 