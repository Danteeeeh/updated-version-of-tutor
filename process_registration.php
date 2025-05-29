<?php
require_once 'includes/config.php';
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Method not allowed");
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Validate inputs
$required = ['student_name', 'student_age', 'parent_name', 'parent_email', 'class_id'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $response['errors'][$field] = "This field is required";
    }
}

// Validate email
if (!filter_var($_POST['parent_email'], FILTER_VALIDATE_EMAIL)) {
    $response['errors']['parent_email'] = "Invalid email format";
}

// Validate age
if ($_POST['student_age'] < 3 || $_POST['student_age'] > 18) {
    $response['errors']['student_age'] = "Age must be between 3 and 18";
}

// If validation errors exist
if (!empty($response['errors'])) {
    $response['message'] = "Please correct the following errors";
    echo json_encode($response);
    exit;
}

// Process registration if no errors
try {
    $stmt = $pdo->prepare("
        INSERT INTO registrations (
            class_id, 
            student_name, 
            student_age, 
            parent_name, 
            parent_email, 
            parent_phone, 
            special_notes,
            registration_date
        ) VALUES (
            :class_id, 
            :student_name, 
            :student_age, 
            :parent_name, 
            :parent_email, 
            :parent_phone, 
            :special_notes,
            NOW()
        )
    ");
    
    // Store POST values in variables before binding
    $class_id = $_POST['class_id'];
    $student_name = $_POST['student_name'];
    $student_age = $_POST['student_age'];
    $parent_name = $_POST['parent_name'];
    $parent_email = $_POST['parent_email'];
    $parent_phone = $_POST['parent_phone'] ?? null;
    $special_notes = $_POST['special_notes'] ?? null;
    
    $stmt->bindParam(':class_id', $class_id);
    $stmt->bindParam(':student_name', $student_name);
    $stmt->bindParam(':student_age', $student_age);
    $stmt->bindParam(':parent_name', $parent_name);
    $stmt->bindParam(':parent_email', $parent_email);
    $stmt->bindParam(':parent_phone', $parent_phone);
    $stmt->bindParam(':special_notes', $special_notes);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Thank you! Your registration has been submitted successfully. We will send you an email with further details.";
        $response['registration_id'] = $pdo->lastInsertId();
        
        // Send confirmation email (pseudo-code)
        /*
        $to = $parent_email;
        $subject = "Registration Confirmation";
        $message = "Thank you for registering for " . $_POST['class_name'] . " with " . $_POST['instructor'];
        $headers = "From: " . SITE_EMAIL;
        mail($to, $subject, $message, $headers);
        */
    } else {
        $response['message'] = "We couldn't process your registration. Please try again.";
    }
} catch(PDOException $e) {
    error_log("Registration error: " . $e->getMessage());
    $response['message'] = "Sorry, we're having technical difficulties. Please try again in a few minutes.";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);