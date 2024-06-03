<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you're receiving form data via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required form fields are present
    if (isset($_POST['id'], $_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
        // Retrieve form data
        $student_id = $_POST['id']; // Assuming 'id' corresponds to 'student_id'
        $username = htmlspecialchars($_POST['name']); // Assuming 'name' corresponds to 'username'
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);
        
        $servername = "localhost"; // Change this to your MySQL server name if it's different
        $db_username = "root"; // Change this to your MySQL username
        $db_password = "S@mim101"; // Change this to your MySQL password
        $database = "portal_extended";
        
        // Now perform the database insertion
        // Connect to your database
        $conn = new mysqli($servername, $db_username, $db_password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert data into the Users table
        $sql = "INSERT INTO Users (student_id, username, email, password_hash, role) 
                VALUES ('$student_id', '$username', '$email', '$password', '$role')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the database connection
        $conn->close();
    } else {
        echo "Required form fields are missing";
    }
}
?>
