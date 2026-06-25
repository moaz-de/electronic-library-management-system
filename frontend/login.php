<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        .glassmorphic {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .gradient {
            background: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex justify-center items-center">
    <div class="glassmorphic w-96 p-10 bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Password">
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
            <p class="text-sm text-gray-500 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (username === '') {
                usernameError.textContent = 'Username is required';
                usernameError.classList.remove('hidden');
                return;
            } else {
                usernameError.classList.add('hidden');
            }

            if (password === '') {
                passwordError.textContent = 'Password is required';
                passwordError.classList.remove('hidden');
                return;
            } else {
                passwordError.classList.add('hidden');
            }

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It includes standard HTML input pattern validators to support Arabic and Latin characters. The form is submitted using AJAX with the Fetch API to the `../backend/auth.php?action=login` endpoint. The response or error is handled dynamically using JavaScript. The page also includes a direct link to the `register.php` page.