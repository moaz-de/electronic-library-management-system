**create_باحثون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO باحثون (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_باحثون.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create new باحثون form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New باحثون</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter email">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter phone">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter address"></textarea>
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:border-indigo-700">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/باحثون.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_باحثون.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/باحثون.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields']);
    } else {
        // Insert data into database
        $query = "INSERT INTO باحثون (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error inserting data']);
        }
    }
}