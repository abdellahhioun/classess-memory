<?php
require 'db_connection.php'; // Inclure la connexion à la base de données

session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header('Location: game.php'); // Rediriger vers la page du jeu si connecté
    exit;
}

// Initialiser les messages d'erreur et de succès
$error = "";
$success = "";

// Traiter la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $action = $_POST['action'] ?? '';

    // Déterminer si l'entrée est un e-mail ou un nom d'utilisateur
    $column = filter_var($user, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if ($action === 'login') {
        // Traitement de la connexion
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE $column = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $password);
            $stmt->fetch();
            
            // Vérifier le mot de passe
            if (password_verify($pass, $password)) {
                // Définir la session et rediriger
                $_SESSION['user'] = $user;
                header('Location: game.php'); // Rediriger vers la page du jeu
                exit;
            } else {
                $error = "Nom d'utilisateur ou mot de passe invalide.";
            }
        } else {
            $error = "Nom d'utilisateur ou mot de passe invalide.";
        }
        $stmt->close();
    } elseif ($action === 'register') {
        // Traitement de l'inscription
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $user, $_POST['email']);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Nom d'utilisateur ou e-mail déjà pris.";
        } else {
            $password_hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user, $_POST['email'], $password_hash);
            if ($stmt->execute()) {
                $success = "Inscription réussie ! Redirection vers la connexion...";
                header('Refresh: 2; URL=index.php'); // Rediriger vers la connexion après 2 secondes
                exit;
            } else {
                $error = "L'inscription a échoué. Veuillez réessayer.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
}
?>

<!-- HTML pour la page d'index -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Register</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f4f8;
        }
            
    .container {
        width: 100%;
        max-width: 397px;
        padding: 20px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: relative;
        overflow: hidden;
        height: 75%;
    }
        .tabs {
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }
        .tabs button {
            flex: 1;
            padding: 15px;
            border: none;
            border-bottom: 3px solid transparent;
            background: transparent;
            cursor: pointer;
            font-size: 16px;
            color: #777;
        }
        .tabs button.active {
            color: #007BFF;
            border-bottom-color: #007BFF;
            font-weight: bold;
        }
        .form-container {
            position: absolute;
            top: 100px;
            left: 0;
            width: 100%;
            transition: opacity 0.6s ease, transform 0.6s ease;
            opacity: 0;
            transform: translateX(100%);
        }
        .form-container.active {
            opacity: 1;
            transform: translateX(0);
        }
        .form-container.hidden {
            opacity: 0;
            transform: translateX(-100%);
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
        }
        .form-container input:focus {
            border-color: #007BFF;
            outline: none;
            background-color: #f0f4ff;
        }
        .form-container input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        .message {
            margin: 20px 0;
            font-size: 1.1em;
            color: #555;
        }
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            .tabs {
                flex-direction: column;
            }
            .tabs button {
                width: 100%;
                padding: 12px;
            }
            .form-container input {
                font-size: 14px;
                padding: 12px;
            }
            .form-container input[type="submit"] {
                font-size: 16px;
                padding: 14px;
            }
        }
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
            <?php
            if (!empty($error)) {
                echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
            }
            if (!empty($success)) {
                echo '<p style="color: green;">' . htmlspecialchars($success) . '</p>';
            }
            ?>
        </div>
    </div>

    <!-- JavaScript pour basculer entre Login et Register -->
    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            registerForm.classList.remove('active');
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
            loginForm.classList.remove('active');
        });
    </script>
</body>
</html>
