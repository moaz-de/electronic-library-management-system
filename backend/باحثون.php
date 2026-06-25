<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['id'])) {
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get single record
    $stmt = $pdo->prepare("SELECT * FROM باحثون WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch();

    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
} elseif (isset($_GET['all'])) {
    // Get all records
    $stmt = $pdo->prepare("SELECT * FROM باحثون");
    $stmt->execute();
    $records = $stmt->fetchAll();

    if ($records) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
} elseif (isset($_GET['search'])) {
    // Search records
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM باحثون WHERE name LIKE :search OR email LIKE :search");
    $stmt->bindParam(':search', '%' . $search . '%');
    $stmt->execute();
    $records = $stmt->fetchAll();

    if ($records) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
} else {
    // Handle POST request
    if (isset($input['name']) && isset($input['email']) && isset($input['role'])) {
        // Validate input
        if (empty($input['name']) || empty($input['email']) || empty($input['role'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $role = filter_var($input['role'], FILTER_SANITIZE_STRING);

        // Insert record
        $stmt = $pdo->prepare("INSERT INTO باحثون (name, email, role) VALUES (:name, :email, :role)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record created successfully'));
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
    }
}

// Handle PUT request
if (isset($_GET['id'])) {
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input
    if (isset($input['name']) && isset($input['email']) && isset($input['role'])) {
        // Sanitize input
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $role = filter_var($input['role'], FILTER_SANITIZE_STRING);

        // Update record
        $stmt = $pdo->prepare("UPDATE باحثون SET name = :name, email = :email, role = :role WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record updated successfully'));
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
    }
}

// Handle DELETE request
if (isset($_GET['id'])) {
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete record
    $stmt = $pdo->prepare("DELETE FROM باحثون WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record deleted successfully'));
}