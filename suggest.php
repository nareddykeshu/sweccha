<?php
// Example backend for saving land condition data

// Database connection
$conn = new mysqli('localhost', 'root', '', 'land_database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $waterAvailability = $_POST['waterAvailability'];
    $phLevel = $_POST['phLevel'];
    $previousPests = $_POST['previousPests'];
    $climateConditions = $_POST['climateConditions'];

    // Insert data into the database
    $query = "INSERT INTO land_conditions (location, water_availability, ph_level, previous_pests, climate_conditions) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $location, $waterAvailability, $phLevel, $previousPests, $climateConditions);

    if ($stmt->execute()) {
        echo "Land condition data saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
