<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Login Page
************/

require('connect.php');

session_start();

// Check if the user is already logged in, redirect to the admin dashboard, user dashboard, or index.php based on role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['is_admin']) {
        $redirect_url = "admin.php";
    } else {
        $redirect_url = "index.php"; // Add the appropriate page for users
    }
    header("Location: $redirect_url");
    exit;
}

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $entered_password = $_POST['password'];

    // Check the credentials against the users table
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($entered_password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['is_admin'] = ($user['role'] === 'admin');

        if ($_SESSION['is_admin']) {
            $redirect_url = "admin.php";
        } else {
            $redirect_url = "index.php"; // Add the appropriate page for users
        }

        header("Location: $redirect_url");
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Login</title>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Panjab Motors</a></h1>
        </div>
        <div id="body">
            <h2>Login</h2>
            <?php if (isset($error_message)) : ?>
                <p style="color: red;"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="post" action="login.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>
</body>
</html>

