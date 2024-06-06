<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$servername = "localhost";
$db_username = "root";
$db_password = "S@mim101";
$database = "portal_extended";

$conn = new mysqli($servername, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = $_POST['course_id'];
$sender_id = $_POST['sender_id'];
$content = $_POST['content'];
$chat_room = 2;


// Fetch the user ID using the sender ID (which is the student ID)
$stmt = $conn->prepare("SELECT user_id FROM Users WHERE student_id = ?");
$stmt->bind_param("i", $sender_id);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    die("User not found");
}
// Fetch the chat room ID using the course ID
$stmt = $conn->prepare("SELECT chat_room_id FROM ChatRooms WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($chat_room_id);
$stmt->fetch();
$stmt->close();

if (!$chat_room_id) {
    die("Chat room not found");
}

// Insert the new message using the fetched user ID
$stmt = $conn->prepare("INSERT INTO Messages (chat_room_id, sender_id, content, chat_room) VALUES (?, ?, ?, $chat_room)");
$stmt->bind_param("iis", $chat_room_id, $user_id, $content);
$stmt->execute();

$stmt->close();
$conn->close();
?>