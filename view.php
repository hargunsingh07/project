<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Car Dealership - View Car Details
************/

require('connect.php');
require('authenticate.php');

// Check if a vehicle_id is provided in the query string
if (isset($_GET['vehicle_id'])) {
    // Sanitize the vehicle_id from the query string
    $vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_SANITIZE_NUMBER_INT);

    // Build the parameterized SQL query to retrieve the specific car details
    $query = "SELECT * FROM vehicles WHERE vehicle_id = :vehicle_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':vehicle_id', $vehicle_id, PDO::PARAM_INT);

    // Execute the SELECT query
    $statement->execute();

    // Fetch the car details from the database
    $car = $statement->fetch();
} else {
    // Redirect to the index page if no vehicle_id is provided
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <style>
        /* Additional styling for controlling image size */
        img {
            max-width: 100%; /* Set the maximum width to the container width */
            height: auto; /* Maintain the aspect ratio */
            display: block; /* Remove extra spacing below the image */
            margin: 10px 0; /* Add some margin for spacing */
        }
    </style>
    <title>View Car Details</title>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Panjab Motors</a></h1>
        </div> 
        <ul id="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="add.php">Add Car</a></li>
        </ul> 
        <div id="content">
            <h2>Car Details</h2>
            <?php if ($car): ?>
                <dl>
                    <dt>Make:</dt>
                    <dd><?= $car['make'] ?></dd>

                    <dt>Model:</dt>
                    <dd><?= $car['model'] ?></dd>

                    <dt>Year:</dt>
                    <dd><?= $car['year'] ?></dd>

                    <dt>Condition:</dt>
                    <dd><?= $car['car_condition'] ?></dd>

                    <dt>Mileage:</dt>
                    <dd><?= $car['mileage'] ?></dd>

                    <dt>Price:</dt>
                    <dd><?= $car['price'] ?></dd>

                    <dt>Description:</dt>
                    <dd><?= $car['description'] ?></dd>

                    <?php if (!empty($car['image'])): ?>
                        <dt>Image:</dt>
                        <dd><img src="<?= $car['image'] ?>" alt="<?= $car['make'] . ' ' . $car['model'] ?>"></dd>
                    <?php endif; ?>
                </dl>
            <?php else: ?>
                <p>Car not found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
