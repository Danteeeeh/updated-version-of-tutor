<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function display_error($error) {
    return '<div class="alert alert-danger">' . $error . '</div>';
}

function display_success($message) {
    return '<div class="alert alert-success">' . $message . '</div>';
}

function get_class_categories($pdo) {
    try {
        $stmt = $pdo->query("SELECT category FROM classes");
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $categories = [];
        foreach($results as $cats) {
            $split = explode(' ', $cats);
            $categories = array_merge($categories, $split);
        }
        
        return array_unique($categories);
    } catch(PDOException $e) {
        error_log("Error getting categories: " . $e->getMessage());
        return [];
    }
}