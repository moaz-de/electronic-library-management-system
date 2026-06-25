<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مكتبة إلكترونية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-slate-900">نظام إدارة مكتبة إلكترونية</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">مرحباً بكم</h2>
            <p class="text-gray-600">هذا هو مركز التحكم الرئيسي لمكتبة إلكترونية</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            $stats = json_decode(file_get_contents('https://example.com/api/stats'), true);
            ?>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold text-slate-900">إحصائيات</h2>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-gray-600">الكتب</p>
                    <p class="text-gray-600"><?= $stats['books'] ?></p>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-gray-600">المقالات</p>
                    <p class="text-gray-600"><?= $stats['articles'] ?></p>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-gray-600">الباحثون</p>
                    <p class="text-gray-600"><?= $stats['researchers'] ?></p>
                </div>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold text-slate-900">الرابط السريع</h2>
                <ul class="list-none mb-0">
                    <li class="mb-2">
                        <a href="books.php" class="text-gray-600 hover:text-gray-900">الكتب</a>
                    </li>
                    <li class="mb-2">
                        <a href="articles.php" class="text-gray-600 hover:text-gray-900">المقالات</a>
                    </li>
                    <li class="mb-2">
                        <a href="researchers.php" class="text-gray-600 hover:text-gray-900">الباحثون</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('https://example.com/api/stats')
            .then(response => response.json())
            .then(stats => {
                const booksElement = document.querySelector('.books');
                const articlesElement = document.querySelector('.articles');
                const researchersElement = document.querySelector('.researchers');
                booksElement.textContent = stats.books;
                articlesElement.textContent = stats.articles;
                researchersElement.textContent = stats.researchers;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: Replace `https://example.com/api/stats` with your actual API endpoint URL that returns the stats data in JSON format.