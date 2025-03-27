<?php
session_start();
require_once('../config/database.php');
require_once('../config/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: dashboard.php');
exit();
?> 