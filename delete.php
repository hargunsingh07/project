<?php
/************
    Name: Hargun Singh
    Date: 2023-09-25
    Description: Admin Dashboard - Delete Car
************/

require('connect.php');
require('authenticate.php');

if ($_GET && isset($_GET['vehicle_id'])) {
    $vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_SANITIZE_NUMBER_INT);

    // Delete the car from the database
    $query = "DELETE FROM vehicles WHERE vehicle_id = :vehicle_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':vehicle_id', $vehicle_id, PDO::PARAM_INT);

    // Execute the DELETE query
    if ($statement->execute()) {
        // Redirect back to the admin dashboard after successful deletion
        header("Location: admin_dashboard.php");
        exit;
    } else {
        // Handle deletion error (you may customize this part based on your needs)
        echo "Error deleting car.";
    }
}
?>
