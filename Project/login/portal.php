<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: /Project/login/login.html');
    exit();
}
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$servername = "localhost";
$db_username = "root";
$db_password = "S@mim101";
$database = "portal_extended";

$conn = new mysqli($servername, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'Student ID not set']);
    exit;
}

$student_id = $_SESSION['student_id'];
$query = "SELECT c.course_id, c.course_name 
          FROM Courses c 
          JOIN CourseParticipants cp ON c.course_id = cp.course_id 
          WHERE cp.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Check if chat rooms exist for each course, if not, create them
foreach ($courses as $course) {
    $course_id = $course['course_id'];
    $check_chatroom_query = "SELECT chat_room_id FROM ChatRooms WHERE course_id = ?";
    $stmt = $conn->prepare($check_chatroom_query);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $chatroom_result = $stmt->get_result();

    if ($chatroom_result->num_rows == 0) {
        $insert_chatroom_query = "INSERT INTO ChatRooms (course_id, name, created_at) 
        VALUES (?, ?, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($insert_chatroom_query);
        $stmt->bind_param('is', $course_id, $course['course_name']);
        $stmt->execute();
    }
}

echo json_encode($courses);

$stmt->close();
$conn->close();
?>
