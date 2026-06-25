**create_مقالات.php**

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);

    // Check for empty fields
    if (empty($title) || empty($content) || empty($author) || empty($category)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مقالات (title, content, author, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $title, $content, $author, $category);
        $stmt->execute();

        // Redirect to list page
        header('Location: list_مقالات.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create New Article</h1>
    <form id="create-article-form" class="space-y-6" method="POST">
        <div class="space-y-2">
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter title">
        </div>
        <div class="space-y-2">
            <label for="content" class="block text-sm font-medium text-slate-900">Content</label>
            <textarea id="content" name="content" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter content"></textarea>
        </div>
        <div class="space-y-2">
            <label for="author" class="block text-sm font-medium text-slate-900">Author</label>
            <input type="text" id="author" name="author" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter author">
        </div>
        <div class="space-y-2">
            <label for="category" class="block text-sm font-medium text-slate-900">Category</label>
            <input type="text" id="category" name="category" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter category">
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Article</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-article-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مقالات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مقالات.php';
                    } else {
                        alert('Error creating article');
                    }
                }
            });
        });
    });
</script>

**Note:** This code assumes that you have already set up a database connection and a backend script (`../backend/مقالات.php`) to handle the form submission. You will need to modify the code to match your specific database schema and backend implementation.