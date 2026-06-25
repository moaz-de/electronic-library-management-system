<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
        <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Register</h2>
            <form id="register-form">
                <div class="mb-4">
                    <label class="block text-slate-900 text-sm font-bold mb-2" for="username">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <p id="username-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-slate-900 text-sm font-bold mb-2" for="email">Email</label>
                    <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Email" required>
                    <p id="email-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-slate-900 text-sm font-bold mb-2" for="password">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" pattern="[A-Za-z0-9!@#$%^&*()_+=-{};:'<>,./?]" required>
                    <p id="password-error" class="text-red-500 hidden"></p>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-bold text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100">Register</button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('register-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const errors = [];

            if (!username.match(pattern)) {
                document.getElementById('username-error').textContent = 'Invalid username';
                document.getElementById('username-error').classList.remove('hidden');
                errors.push('username');
            } else {
                document.getElementById('username-error').classList.add('hidden');
            }

            if (!email) {
                document.getElementById('email-error').textContent = 'Email is required';
                document.getElementById('email-error').classList.remove('hidden');
                errors.push('email');
            } else if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                document.getElementById('email-error').textContent = 'Invalid email';
                document.getElementById('email-error').classList.remove('hidden');
                errors.push('email');
            } else {
                document.getElementById('email-error').classList.add('hidden');
            }

            if (!password) {
                document.getElementById('password-error').textContent = 'Password is required';
                document.getElementById('password-error').classList.remove('hidden');
                errors.push('password');
            } else if (!password.match(pattern)) {
                document.getElementById('password-error').textContent = 'Invalid password';
                document.getElementById('password-error').classList.remove('hidden');
                errors.push('password');
            } else {
                document.getElementById('password-error').classList.add('hidden');
            }

            if (errors.length === 0) {
                try {
                    const response = await fetch('../backend/auth.php?action=register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ username, email, password })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Registration successful!');
                        form.reset();
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        });
    </script>
</body>
</html>


Please note that you need to replace `../backend/auth.php` with the actual path to your backend script. Also, make sure to handle the registration process in the backend script to store the user's data in your database. 

This code uses the `fetch` API to send a POST request to the backend script with the user's data. The backend script should handle the request, validate the data, and store it in the database if everything is valid. 

The `pattern` variable is used to validate the username and password inputs. You can adjust this pattern according to your requirements. 

The error messages are displayed below each input field. If the input is invalid, the error message is displayed, and the input field is highlighted in red. 

The registration process is handled in the `submit` event listener of the form. When the form is submitted, the event listener checks if all input fields are valid. If they are, it sends a POST request to the backend script with the user's data. If the registration is successful, an alert is displayed, and the form is reset. If there's an error, an alert is displayed with the error message.