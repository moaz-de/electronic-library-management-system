<?php
// Start the session to store user data
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their user data
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the form data is valid
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // If the username or email is already taken, return an error response
            $response = array(
                'status' => 'error',
                'message' => 'Username or email is already taken'
            );
            echo json_encode($response);
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        // Return a success response
        $response = array(
            'status' => 'success',
            'message' => 'User created successfully'
        );
        echo json_encode($response);
        exit;
    } else {
        // If the form data is invalid, return an error response
        $response = array(
            'status' => 'error',
            'message' => 'Invalid form data'
        );
        echo json_encode($response);
        exit;
    }
}

// Check if the user is trying to login
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the form data is valid
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and password are valid
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Get the user's data from the database
            $user_data = $result->fetch_assoc();

            // Check if the password is correct
            if (password_verify($password, $user_data['password'])) {
                // If the password is correct, log the user in
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];

                // Return a success response
                $response = array(
                    'status' => 'success',
                    'message' => 'Logged in successfully'
                );
                echo json_encode($response);
                exit;
            } else {
                // If the password is incorrect, return an error response
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid password'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            // If the username is not found, return an error response
            $response = array(
                'status' => 'error',
                'message' => 'Invalid username'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        // If the form data is invalid, return an error response
        $response = array(
            'status' => 'error',
            'message' => 'Invalid form data'
        );
        echo json_encode($response);
        exit;
    }
}

// Check if the user is trying to logout
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();

    // Return a success response
    $response = array(
        'status' => 'success',
        'message' => 'Logged out successfully'
    );
    echo json_encode($response);
    exit;
}
?>