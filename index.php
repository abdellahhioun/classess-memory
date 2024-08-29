<?php
require 'db_connection.php';
session_start();

// Check if user is already logged in
if (isset($_SESSION['user'])) {
    header('Location: game.php'); // Redirect to the game page
    exit;
}

// Initialize error and success messages
$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $action = $_POST['action'] ?? '';

    // Determine if input is an email or username
    $column = filter_var($user, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if ($action === 'login') {
        // Handle login
        $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE $column = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $password_hash);
            $stmt->fetch();
            
            // Verify the password
            if (password_verify($pass, $password_hash)) {
                // Set session and redirect
                $_SESSION['user'] = $user;
                header('Location: game.php'); // Redirect to the game page
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    } elseif ($action === 'register') {
        // Handle registration
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $user, $user); // Check both username and email
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Username or email already taken.";
        } else {
            $password_hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user, $_POST['email'], $password_hash);
            if ($stmt->execute()) {
                $success = "Registration successful! Redirecting to login...";
                header('Refresh: 2; URL=index.php'); // Redirect to login after 2 seconds
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Register</title>
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <!-- Tabs -->
        <div class="tabs">
            <button id="loginTab" class="active">Login</button>
            <button id="registerTab">Register</button>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="form-container active">
            <h2>Login</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="text" name="username" placeholder="Username or Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="hidden" name="action" value="login">
                <input type="submit" value="Login">
            </form>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="form-container hidden">
            <h2>Register</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="hidden" name="action" value="register">
                <input type="submit" value="Register">
            </form>
        </div>

        <!-- Message Display -->
        <div class="message">
            <!-- Dynamic messages will appear here -->
        </div>
    </div>

    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        function switchForm(activeForm, inactiveForm, activeTab, inactiveTab) {
            inactiveForm.classList.remove('active');
            inactiveForm.classList.add('hidden');
            activeForm.classList.remove('hidden');
            setTimeout(() => {
                activeForm.classList.add('active');
            }, 20);

            activeTab.classList.add('active');
            inactiveTab.classList.remove('active');
        }

        loginTab.addEventListener('click', () => {
            if (!loginForm.classList.contains('active')) {
                switchForm(loginForm, registerForm, loginTab, registerTab);
            }
        });

        registerTab.addEventListener('click', () => {
            if (!registerForm.classList.contains('active')) {
                switchForm(registerForm, loginForm, registerTab, loginTab);
            }
        });
    </script>
</body>
</html>
