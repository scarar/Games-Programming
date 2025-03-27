<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-nav .nav-links {
            display: flex;
            gap: 15px;
        }
        .admin-nav .btn {
            padding: 8px 16px;
        }
        .main-content {
            margin-top: 70px;
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="nav-brand">
            <h4 class="mb-0">Admin Panel</h4>
        </div>
        <div class="nav-links">
            <a href="../index.php" class="btn btn-outline-primary">View Site</a>
            <a href="posts.php" class="btn btn-outline-secondary">Manage Posts</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="main-content"> 