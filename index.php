<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Car Dealership
************/

require('connect.php');

session_start();

$query = "SELECT * FROM vehicles "; // Assuming your table is named 'vehicles'
$statement = $db->prepare($query);
$statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Welcome to Panjab Motors!</title>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Panjab Motors</a></h1>
        </div> 
        <ul id="menu">
            <li><a href="index.php" class="active">Home</a></li>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <!-- Display Logout button when a user is logged in -->
                <li style="float:right"><a href="logout.php">Logout</a></li>
            <?php else : ?>
                <!-- Display Login button when no user is logged in -->
                <li style="float:right"><a href="login.php">Login</a></li>
            <?php endif; ?>

        </ul> 
        <div id="body">
        <h2>Recently Added Cars</h2>
        <ul id="ulist">
            <?php while($row = $statement->fetch()) : ?>
                <li>
                    <h3><a href="view.php?vehicle_id=<?= $row['vehicle_id']; ?>"><?= $row['make'] ?> <?= $row['model'] ?></a></h3>
                </li>
                <li><img src="<?= $row['image'] ?>" alt="<?= $row['make'] ?> <?= $row['model'] ?> Image" style="max-width: 200px; max-height: 200px"></li>
                <li>Year: <?= $row['year'] ?></li>
                <li>Condition: <?= $row['car_condition'] ?></li>
                <li>Mileage: <?= $row['mileage'] ?> miles</li>
                <li>Price: $<?= $row['price'] ?></li>
                <li>Description: <?= $row['description'] ?></li>
            <?php endwhile ?>
        </ul>
    </div>
    <div id="footer">
        Copyright 2023 - All Rights Reserved
    </div> 
</div> 
</body>
</html>







