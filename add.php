<?php
/************
    Name: Hargun Singh
    Date:
    Description: Car Dealership - Add Car
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

if ($_POST && !empty($_POST['make']) && !empty($_POST['model']) && !empty($_POST['year']) && !empty($_POST['car_condition']) && !empty($_POST['mileage']) && !empty($_POST['price']) && !empty($_POST['description'])) {
    $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
    $car_condition = filter_input(INPUT_POST, 'car_condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $mileage = filter_input(INPUT_POST, 'mileage', FILTER_SANITIZE_NUMBER_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
        } else {
            // Unsupported file type
            echo "Unsupported file type. Please upload a supported image (JPEG, PNG, GIF).";
            exit;
        }
    } else {
        // No image uploaded
        $image_path = null;
    }

    // Build the parameterized SQL query
    $query = "INSERT INTO vehicles (make, model, year, car_condition, mileage, price, description, image) 
              VALUES (:make, :model, :year, :car_condition, :mileage, :price, :description, :image)";
    $statement = $db->prepare($query);

    // Bind values to the parameters
    $statement->bindValue(':make', $make);
    $statement->bindValue(':model', $model);
    $statement->bindValue(':year', $year);
    $statement->bindValue(':car_condition', $car_condition);
    $statement->bindValue(':mileage', $mileage);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':image', $image_path);

    // Execute the INSERT.
    if ($statement->execute()) {
        echo "Success";
    } else {
        echo "Error inserting data into the database.";
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
    <title>Add New Car</title>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Panjab Motors</a></h1>
        </div> 
        <ul id="menu">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="add.php">Add Car</a></li>
        </ul> 
        <form method="post" action="add.php" enctype="multipart/form-data">
            <label for="make">Make</label>
            <input id="make" name="make" required>
            
            <label for="model">Model</label>
            <input id="model" name="model" required>

            <label for="year">Year</label>
            <input type="number" id="year" name="year" required>

            <label for="car_condition">Condition</label>
            <input id="car_condition" name="car_condition" required>

            <label for="mileage">Mileage</label>
            <input type="number" id="mileage" name="mileage" required>

            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="uploaded_image">Upload Image:</label>
            <input type="file" id="uploaded_image" name="uploaded_image" accept="image/jpeg, image/png, image/gif">
            
            <button type="submit">Add Car</button>
        </form>
        
    </div>
</body>
</html>
