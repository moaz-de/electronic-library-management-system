**create_تصنيفات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

// Include header and navigation
include '../includes/header.php';
include '../includes/navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة تصنيف</h2>
        <form id="create-category-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم التصنيف</label>
                    <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="block text-slate-900 text-sm font-bold mb-2">وصف التصنيف</label>
                    <textarea id="description" name="description" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-category-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/تصنيفات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = '../list_تصنيفات.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include '../includes/footer.php';
?>


**backend/تصنيفات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

// Validate form data
$name = trim($_POST['name']);
$description = trim($_POST['description']);

// Insert new category
$query = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Category added successfully'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error adding category'));
}
?>


Note: This code assumes you have a MySQL database connection established in `backend/تصنيفات.php`. You should replace `$conn` with your actual database connection variable. Also, this code does not include any error handling or security measures, such as sanitizing user input or checking for SQL injection attempts. You should add these measures to your production code.