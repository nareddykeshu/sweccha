<?php
// Start a session to manage user data
session_start();

// Database connection details (update with your database credentials)
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'user_database';

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the registration form submission
if (isset($_POST['signUp'])) {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

    // Check if the email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists. Please try another.";
    } else {
        // Insert new user into the database
        $insertQuery = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('ssss', $fName, $lName, $email, $password);

        if ($stmt->execute()) {
            echo "Registration successful. You can now log in.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Handle the login form submission
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['first_name']; // Store user data in the session
            echo "Login successful. Welcome, " . $user['first_name'] . "!";
        } else {
            echo "Invalid password. Please try again.";
        }
    } else {
        echo "No user found with that email. Please register.";
    }
}

// Close the database connection
$conn->close();
?>
