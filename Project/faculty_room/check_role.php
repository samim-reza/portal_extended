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

$course_id = $_GET['course_id'] ?? null;
$student_id = $_GET['student_id'] ?? null;
$course_name = "";

if ($course_id) {
  
  $query = "SELECT role FROM CourseParticipants WHERE course_id = $course_id AND student_id = $student_id";
  $stmt = $conn->prepare($query);
  // $stmt->bind_param("ii", $course_id, $student_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
    
    if ($role == 'CR' || $role == 'cr') {
      echo 'yes';
    } elseif ($role == 'teacher') {
      echo 'yes';
    } elseif ($role == 'student') {
      echo 'no';
    }
  } else {
    echo 'no';
  }

  $stmt->close();
}
$conn->close();

?>