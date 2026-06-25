**list_كتب.php**

<?php
// Session validation
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
    <title>كتب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #ffffff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز الكتب</span>
        <span class="text-lg font-bold">مركز الكتب</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة الكتب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_كتب.php'">إضافة كتاب جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchBooks()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان الكتاب</th>
                    <th>كاتب الكتاب</th>
                    <th>تاريخ النشر</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="book-list">
                <?php
                // Fetch list records from backend
                $response = file_get_contents('../backend/كتب.php');
                $books = json_decode($response, true);
                foreach ($books as $book) {
                    echo '<tr>';
                    echo '<td>' . $book['title'] . '</td>';
                    echo '<td>' . $book['author'] . '</td>';
                    echo '<td>' . $book['publication_date'] . '</td>';
                    echo '<td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteBook(' . $book['id'] . ')">حذف</button></td>';
                    echo '<td><a href="edit_كتب.php?id=' . $book['id'] . '" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchBooks() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/كتب.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(books => {
                        const bookList = document.getElementById('book-list');
                        bookList.innerHTML = '';
                        books.forEach(book => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.publication_date}</td>
                                <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteBook(${book.id})">حذف</button></td>
                                <td><a href="edit_كتب.php?id=${book.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                            `;
                            bookList.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/كتب.php')
                    .then(response => response.json())
                    .then(books => {
                        const bookList = document.getElementById('book-list');
                        bookList.innerHTML = '';
                        books.forEach(book => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.publication_date}</td>
                                <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteBook(${book.id})">حذف</button></td>
                                <td><a href="edit_كتب.php?id=${book.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                            `;
                            bookList.appendChild(row);
                        });
                    });
            }
        }

        function deleteBook(id) {
            if (confirm('هل أنت متأكد من حذف الكتاب؟')) {
                fetch('../backend/كتب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الكتاب بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الكتاب');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`كتب.php`) that returns a JSON array of books, and another script (`create_كتب.php`) that handles the creation of new books. You will need to modify the code to match your specific backend implementation.