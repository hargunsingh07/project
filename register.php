<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Registration Page
************/

require('connect.php');

session_start();

// Check if the user is already logged in, redirect to the index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Handle registration attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // Password should be hashed in a real-world scenario

    // Insert the new user into the users table with the role 'user'
    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':password', $password, PDO::PARAM_STR);

    if ($statement->execute()) {
        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['is_admin'] = false;

        header("Location: index.php");
        exit;
    } else {
        $error_message = "Registration failed. Please try again.";
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
    <title>Register</title>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Panjab Motors</a></h1>
        </div>
        <div id="body">
            <h2>Register</h2>
            <?php if (isset($error_message)) : ?>
                <p style="color: red;"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="post" action="register.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
