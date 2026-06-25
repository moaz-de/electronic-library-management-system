**edit_مقالات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get article ID from URL
$id = $_GET['id'];

// Fetch article details via AJAX
$article = json_decode(file_get_contents('../backend/مقالات.php?id=' . $id), true);

// Check if article exists
if (empty($article)) {
    echo 'Article not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Article';
$mod_slug = 'مقالات';

// Include header and navigation
require_once '../includes/header.php';
require_once '../includes/navigation.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>
    <form id="edit-article-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
            <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $article['title'] ?>">
        </div>
        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
            <textarea id="content" name="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $article['content'] ?></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Article</button>
    </form>
</main>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // Fetch article details via AJAX
    fetch('../backend/مقالات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('content').value = data.content;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-article-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send PUT request to update article
        fetch('../backend/مقالات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to article list page
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<style>
    /* Custom styles for Tailwind UI form */
    .bg-slate-900 {
        background-color: #1a1d23;
    }

    .text-indigo-500 {
        color: #6b6ecf;
    }

    .bg-indigo-500 {
        background-color: #6b6ecf;
    }

    .hover:bg-indigo-700 {
        background-color: #5a5c7e;
    }
</style>


**backend/مقالات.php**

<?php
// Check if article ID is set
if (!isset($_GET['id'])) {
    echo 'Article ID not set';
    exit;
}

// Get article ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch article details
$query = "SELECT * FROM articles WHERE id = '$id'";

// Execute query
$result = $conn->query($query);

// Check if article exists
if ($result->num_rows > 0) {
    // Fetch article details
    $article = $result->fetch_assoc();
    echo json_encode($article);
} else {
    echo 'Article not found';
}

// Close database connection
$conn->close();
?>


**backend/update_article.php**

<?php
// Check if article ID is set
if (!isset($_GET['id'])) {
    echo 'Article ID not set';
    exit;
}

// Get article ID
$id = $_GET['id'];

// Get form data
$title = $_POST['title'];
$content = $_POST['content'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to update article
$query = "UPDATE articles SET title = '$title', content = '$content' WHERE id = '$id'";

// Execute query
$conn->query($query);

// Close database connection
$conn->close();

// Redirect to article list page
header('Location: list_<?= $mod_slug ?>.php');
exit;
?>