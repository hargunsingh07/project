<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Admin Dashboard - View Cars
************/

require('connect.php');
require('authenticate.php');

// Fetch all cars from the database
$query = "SELECT * FROM vehicles";
$statement = $db->prepare($query);
$statement->execute();
$cars = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Admin Dashboard - View Cars</title>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Panjab Motors</a></h1>
        </div> 
        <ul id="menu">
            <li><a href="index.php">Home</a></li>
            <!-- Add more links as needed -->
        </ul> 
        <div id="content">
            <h2>Admin Dashboard - View Cars</h2>
            <table>
                <thead>
                    <tr>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Condition</th>
                        <th>Mileage</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?= $car['make'] ?></td>
                            <td><?= $car['model'] ?></td>
                            <td><?= $car['year'] ?></td>
                            <td><?= $car['car_condition'] ?></td>
                            <td><?= $car['mileage'] ?></td>
                            <td><?= $car['price'] ?></td>
                            <td><?= $car['description'] ?></td>
                            <td>
                                <?php if (!empty($car['image'])): ?>
                                    <img src="<?= $car['image'] ?>" alt="<?= $car['make'] . ' ' . $car['model'] ?>" style="max-width: 50px; max-height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?vehicle_id=<?= $car['vehicle_id'] ?>">Edit</a>
                                <a href="delete.php?vehicle_id=<?= $car['vehicle_id'] ?>" onclick="return confirm('Are you sure you want to delete this car?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
