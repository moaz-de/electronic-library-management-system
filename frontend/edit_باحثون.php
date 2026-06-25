**edit_باحثون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/باحثون.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Researcher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Researcher</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" value="<?= $data['name'] ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                <input type="email" id="email" name="email" value="<?= $data['email'] ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?= $data['phone'] ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-300">Update Researcher</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const response = await fetch('../backend/باحثون.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: <?= $id ?>,
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value
                })
            });
            if (response.ok) {
                window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
            } else {
                console.error('Error updating researcher:', response.statusText);
            }
        });
    </script>
</body>
</html>


**backend/باحثون.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get id from URL
$id = $_GET['id'];

// Query to fetch existing record details
$query = "SELECT * FROM researchers WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    http_response_code(404);
    exit;
}

// Close connection
$conn->close();
?>


**Note:** This code assumes that you have a database table named `researchers` with columns `id`, `name`, `email`, and `phone`. You should replace the placeholders with your actual database credentials and table structure. Additionally, this code uses a simple `file_get_contents` call to fetch the existing record details, which may not be suitable for production environments. Consider using a more secure method, such as an API call or a database query.