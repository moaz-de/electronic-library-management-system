**list_تصنيفات.php**

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
    <title>تصنيفات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
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
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="mx-2">|</span>
        <span><?= $_SESSION['username'] ?></span>
        <span class="mx-2">|</span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تصنيفات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_تصنيفات.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم التصنيف</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children).filter((record) => {
                const text = record.textContent.toLowerCase();
                return text.includes(searchQuery);
            });
            recordsTable.innerHTML = '';
            records.forEach((record) => recordsTable.appendChild(record));
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/تصنيفات.php', { method: 'GET' });
                const data = await response.json();
                const recordsHtml = data.map((record) => {
                    return `
                        <tr>
                            <td>${record.اسم_التصنيف}</td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td>
                                <a href="edit_تصنيفات.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        </tr>
                    `;
                }).join('');
                recordsTable.innerHTML = recordsHtml;
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/تصنيفات.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    loadRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>


**backend/تصنيفات.php**

<?php
// Assuming you have a database connection established
// and a function to fetch records from the database

function fetchRecords() {
    // Fetch records from the database
    // and return them as an array
}

function deleteRecord($id) {
    // Delete a record from the database
    // and return true if successful, false otherwise
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $records = fetchRecords();
    echo json_encode($records);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    if (deleteRecord($id)) {
        echo 'Record deleted successfully';
    } else {
        echo 'Error deleting record';
    }
}
?>