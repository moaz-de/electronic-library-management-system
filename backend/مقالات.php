<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    'GET' => [
        '/' => function() {
            // Get all articles
            $stmt = $pdo->prepare('SELECT * FROM مقالات');
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($articles);
        },
        '/:id' => function($id) {
            // Get article by ID
            $stmt = $pdo->prepare('SELECT * FROM مقالات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$article) {
                http_response_code(404);
                echo json_encode(['error' => 'Article not found']);
                exit;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($article);
        }
    ],
    'POST' => function() {
        // Validate input data
        if (!isset($input['title']) || !isset($input['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Sanitize input data
        $title = htmlspecialchars($input['title']);
        $content = htmlspecialchars($input['content']);
        
        // Insert new article
        $stmt = $pdo->prepare('INSERT INTO مقالات (title, content) VALUES (:title, :content)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
        
        // Get ID of newly inserted article
        $id = $pdo->lastInsertId();
        
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
    },
    'PUT' => function() {
        // Validate input data
        if (!isset($input['id']) || !isset($input['title']) || !isset($input['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($input['id']);
        $title = htmlspecialchars($input['title']);
        $content = htmlspecialchars($input['content']);
        
        // Check if user is admin
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Update article
        $stmt = $pdo->prepare('UPDATE مقالات SET title = :title, content = :content WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Article updated successfully']);
    },
    'DELETE' => function() {
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($input['id']);
        
        // Check if user is admin
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Delete article
        $stmt = $pdo->prepare('DELETE FROM مقالات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Article deleted successfully']);
    }
];

// Determine request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (isset($routes[$method])) {
    $route = $routes[$method];
    if (isset($input['id'])) {
        $route = $route['/:id'];
    } else {
        $route = $route['/'];
    }
    $route($input['id'] ?? null);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}