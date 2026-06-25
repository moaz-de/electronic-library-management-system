**create_كتب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة كتاب جديد</h2>
        <form id="create-book-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="text-slate-900 font-bold">العنوان</label>
                    <input type="text" id="title" name="title" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="author" class="text-slate-900 font-bold">المؤلف</label>
                    <input type="text" id="author" name="author" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label for="description" class="text-slate-900 font-bold">الوصف</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-book-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/كتب.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_كتب.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/كتب.php**

<?php
// Check if form data is submitted
if (isset($_POST['title']) && isset($_POST['author']) && isset($_POST['description'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL query
    $sql = "INSERT INTO كتب (title, author, description) VALUES (?, ?, ?)";
    
    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $_POST['title'], $_POST['author'], $_POST['description']);
    
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    
    // Close connection
    $conn->close();
} else {
    echo 'Error: No form data submitted';
}
?>