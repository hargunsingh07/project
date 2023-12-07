<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Car Dealership - Edit Car
************/

require('connect.php');
require('authenticate.php');

// Function to check if a file is an image
function file_is_an_image($temporary_path) {
    $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
    $actual_mime_type = mime_content_type($temporary_path);
    return in_array($actual_mime_type, $allowed_mime_types);
}

// Function to generate the upload path
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);

    // Build an array of paths segment names to be joined using OS specific slashes.
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];

    // The DIRECTORY_SEPARATOR constant is OS specific.
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

// UPDATE vehicle if make, model, year, and vehicle_id are present in the form.
if ($_POST && isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['car_condition']) && isset($_POST['mileage']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['vehicle_id'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
    $car_condition = filter_input(INPUT_POST, 'car_condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $mileage = filter_input(INPUT_POST, 'mileage', FILTER_SANITIZE_NUMBER_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $vehicle_id = filter_input(INPUT_POST, 'vehicle_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Check if an image was uploaded
    if (isset($_FILES['uploaded_image']) && $_FILES['uploaded_image']['error'] === 0) {
        $image_filename = $_FILES['uploaded_image']['name'];
        $temporary_image_path = $_FILES['uploaded_image']['tmp_name'];
        $new_image_path = file_upload_path($image_filename);

        // Check if the file is an image
        if (file_is_an_image($temporary_image_path)) {
            // Move the uploaded image to the "uploads" folder
            move_uploaded_file($temporary_image_path, $new_image_path);

            // Save the relative URL path to the database
            $image_path = 'uploads/' . $image_filename;

            // Build the parameterized SQL query to update the image path
            $image_query = "UPDATE vehicles SET image = :image WHERE vehicle_id = :vehicle_id";
            $image_statement = $db->prepare($image_query);
            $image_statement->bindValue(':image', $image_path);
            $image_statement->bindValue(':vehicle_id', $vehicle_id, PDO::PARAM_INT);
            $image_statement->execute();
        } else {
            // Unsupported file type
            echo "Unsupported file type. Please upload a supported image (JPEG, PNG, GIF).";
            exit;
        }
    }

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "UPDATE vehicles SET make = :make, model = :model, year = :year, car_condition = :car_condition, mileage = :mileage, price = :price, description = :description WHERE vehicle_id = :vehicle_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':make', $make);
    $statement->bindValue(':model', $model);
    $statement->bindValue(':year', $year);
    $statement->bindValue(':car_condition', $car_condition);
    $statement->bindValue(':mileage', $mileage);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':vehicle_id', $vehicle_id, PDO::PARAM_INT);
    
    // Execute the UPDATE.
    $statement->execute();
    
    // Redirect after update.
    header("Location: index.php");
    exit;
} elseif (isset($_GET['vehicle_id'])) { // Retrieve car to be edited, if vehicle_id GET parameter is in URL.
    // Sanitize the vehicle_id. Like above but this time from INPUT_GET.
    $vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered vehicle_id.
    $query = "SELECT * FROM vehicles WHERE vehicle_id = :vehicle_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':vehicle_id', $vehicle_id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $car = $statement->fetch();
} else {
    $vehicle_id = false; // False if we are not UPDATING or SELECTING.
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit Car Details</title>
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
        <?php if ($vehicle_id): ?>
            <form method="post" enctype="multipart/form-data">
                <!-- Hidden input for the car primary key. -->
                <input type="hidden" name="vehicle_id" value="<?= $car['vehicle_id'] ?>">
                
                <!-- Car details are echoed into the input value attributes. -->
                <label for="make">Make</label>
                <input id="make" name="make" value="<?= $car['make'] ?>" required>

                <label for="model">Model</label>
                <input id="model" name="model" value="<?= $car['model'] ?>" required>

                <label for="year">Year</label>
                <input type="number" id="year" name="year" value="<?= $car['year'] ?>" required>

                <label for="car_condition">Condition</label>
                <input id="car_condition" name="car_condition" value="<?= $car['car_condition'] ?>" required>

                <label for="mileage">Mileage</label>
                <input type="number" id="mileage" name="mileage" value="<?= $car['mileage'] ?>" required>

                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" value="<?= $car['price'] ?>" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" required><?= $car['description'] ?></textarea>

                <label for="uploaded_image">Upload New Image:</label>
                <input type="file" id="uploaded_image" name="uploaded_image" accept="image/jpeg, image/png, image/gif">
                
                <input type="submit" name="command" value="Update" />
               
            </form>
        <?php endif ?>
    </div>
</body>
</html>

