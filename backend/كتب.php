<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array('/' => 'getAll', '/:id' => 'getById'),
    'POST' => '/create',
    'PUT' => array('/:id' => 'update'),
    'DELETE' => array('/:id' => 'delete')
);

// Get current route
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Check if route exists
if (!isset($routes[$method][$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Call corresponding function
$func = $routes[$method][$route];
$func();

// Function to get all books
function getAll() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM كتب');
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($books);
}

// Function to get book by id
function getById() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM كتب WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$book) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($book);
}

// Function to create new book
function create() {
    global $pdo;
    if (!isset($input['title']) || !isset($input['author'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $title = $pdo->quote($input['title']);
    $author = $pdo->quote($input['author']);
    $stmt = $pdo->prepare('INSERT INTO كتب (title, author) VALUES (' . $title . ', ' . $author . ')');
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Book created successfully'));
}

// Function to update book
function update() {
    global $pdo;
    $id = $_GET['id'];
    if (!isset($input['title']) || !isset($input['author'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $title = $pdo->quote($input['title']);
    $author = $pdo->quote($input['author']);
    $stmt = $pdo->prepare('UPDATE كتب SET title = ' . $title . ', author = ' . $author . ' WHERE id = :id');
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Book updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
        exit;
    }
}

// Function to delete book
function delete() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM كتب WHERE id = :id');
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Book deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
        exit;
    }
}
?>