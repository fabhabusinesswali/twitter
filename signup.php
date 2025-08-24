<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter Clone - Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #15202B;
            color: white;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #192734;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo i {
            color: #1DA1F2;
            font-size: 40px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            background-color: #253341;
            border-radius: 5px;
            color: white;
        }

        .btn {
            background-color: #1DA1F2;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 25px;
            width: 100%;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
        }

        .btn:hover {
            background-color: #1991db;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #1DA1F2;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fab fa-twitter"></i>
        </div>
        <form id="signupForm">
            <input type="text" id="username" placeholder="Username" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/your-code.js"></script>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            fetch('process_signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: username,
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.href = 'home.php';
                } else {
                    alert(data.message);
                }
            });
        });
    </script>
</body>
</html> 
