<?php
// Searching Products Script

require('connect.php');

// Retrieve the search keyword from the URL
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// SQL query to search for pages based on 'make' and 'model'
$query = "SELECT * FROM vehicles WHERE make LIKE :keyword OR model LIKE :keyword";
$statement = $db->prepare($query);
$statement->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
$statement->execute();

// Fetch the first result
$foundPage = $statement->fetch(PDO::FETCH_ASSOC);

// Redirect to view.php if a result is found
if ($foundPage) {
    header("Location: view.php?vehicle_id={$foundPage['vehicle_id']}");
    exit;
} else {
    // Redirect to index.php if no result is found
    header("Location: index.php");
    exit;
}
