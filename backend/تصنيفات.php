<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array('/categories', '/categories/:id'),
    'POST' => '/categories',
    'PUT' => '/categories/:id',
    'DELETE' => '/categories/:id'
);

// Define validation rules
$validationRules = array(
    'name' => array('required' => true, 'min' => 3, 'max' => 50)
);

// Process request
$method = $_SERVER['REQUEST_METHOD'];
$route = $routes[$method][0];
if (isset($input['id'])) {
    $route = str_replace(':id', $input['id'], $route);
}

// Validate input data
if ($method === 'POST' || $method === 'PUT') {
    $errors = array();
    foreach ($validationRules as $field => $rules) {
        if (isset($input[$field])) {
            if (isset($rules['required']) && empty($input[$field])) {
                $errors[] = $field . ' is required';
            }
            if (isset($rules['min']) && strlen($input[$field]) < $rules['min']) {
                $errors[] = $field . ' must be at least ' . $rules['min'] . ' characters';
            }
            if (isset($rules['max']) && strlen($input[$field]) > $rules['max']) {
                $errors[] = $field . ' must not exceed ' . $rules['max'] . ' characters';
            }
        }
    }
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(array('error' => $errors));
        exit;
    }
}

// Sanitize input data
$input = array_map('trim', $input);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Process request
switch ($method) {
    case 'GET':
        if (strpos($route, ':id') !== false) {
            $id = $input['id'];
            $stmt = $db->prepare('SELECT * FROM categories WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $category = $stmt->fetch();
            if ($category) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($category);
            } else {
                http_response_code(404);
                echo json_encode(array('error' => 'Category not found'));
            }
        } else {
            $stmt = $db->prepare('SELECT * FROM categories');
            $stmt->execute();
            $categories = $stmt->fetchAll();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($categories);
        }
        break;
    case 'POST':
        if ($_SESSION['user_role'] === 'admin') {
            $stmt = $db->prepare('INSERT INTO categories (name) VALUES (:name)');
            $stmt->bindParam(':name', $input['name']);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Category created successfully'));
        } else {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
        }
        break;
    case 'PUT':
        if ($_SESSION['user_role'] === 'admin') {
            $id = $input['id'];
            $stmt = $db->prepare('UPDATE categories SET name = :name WHERE id = :id');
            $stmt->bindParam(':name', $input['name']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Category updated successfully'));
        } else {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
        }
        break;
    case 'DELETE':
        if ($_SESSION['user_role'] === 'admin') {
            $id = $input['id'];
            $stmt = $db->prepare('DELETE FROM categories WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Category deleted successfully'));
        } else {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
        }
        break;
}

// Close database connection
$db = null;

?>