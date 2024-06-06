<?php
$servername = "localhost";
$db_username = "root";
$db_password = "S@mim101";
$database = "portal_extended";

$conn = new mysqli($servername, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$chat_room = $_GET['chat_room'] ?? null;
$course_id = $_GET['course_id'] ?? null;

if ($chat_room && $course_id) {
    $messages = [];
    $stmt = $conn->prepare("SELECT content, sent_at, sender_id, (SELECT username FROM Users WHERE user_id = sender_id) AS sender FROM Messages WHERE chat_room_id = ? AND chat_room = 1");
    $stmt->bind_param("i", $chat_room);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();

    echo json_encode($messages);
} else {
    echo json_encode([]);
}

$conn->close();
?>
