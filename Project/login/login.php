<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "S@mim101";
$database = "portal_extended";

$conn = new mysqli($servername, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function verify_login($conn, $student_id, $password)
{
    $sql = "SELECT password_hash FROM Users WHERE student_id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password_hash'];
        if (password_verify($password, $hashed_password)) {
            return true;
        }
    }
    return false;
}

if (isset($_POST['id']) && isset($_POST['password'])) {
    $student_id = $_POST['id'];
    $password = $_POST['password'];
    if (verify_login($conn, $student_id, $password)) {

        $_SESSION['student_id'] = $student_id;

        echo "success";
    } else {
        echo "failure";
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>