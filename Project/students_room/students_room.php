<?php
$servername = "localhost";
$db_username = "root";
$db_password = "S@mim101";
$database = "portal_extended";

$conn = new mysqli($servername, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = $_GET['course_id'] ?? null;
$student_id = $_GET['studentid'] ?? null;
$course_name = "";
$chat_room_id = null;

if ($course_id && $student_id) {
    // Retrieve the course name
    $stmt = $conn->prepare("SELECT course_name FROM Courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($course_name);
    $stmt->fetch();
    $stmt->close();

    // Retrieve the chat room ID based on the course ID
    $stmt = $conn->prepare("SELECT chat_room_id FROM ChatRooms WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($chat_room_id);
    $stmt->fetch();
    $stmt->close();

    if (!$chat_room_id) {
        die("Chat room not found");
    }

    // Retrieve the user ID based on the student ID
    $stmt = $conn->prepare("SELECT user_id FROM Users WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($logged_in_user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$logged_in_user_id) {
        die("User not found");
    }

    // Retrieve messages for the chat room
    $messages = [];
    $stmt = $conn->prepare("SELECT content, sent_at, sender_id, (SELECT username FROM Users WHERE user_id = sender_id) AS sender FROM Messages WHERE chat_room_id = ? AND chat_room = 2");
    $stmt->bind_param("i", $chat_room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['is_sender'] = $row['sender_id'] == $logged_in_user_id;
        $messages[] = $row;
    }
    $stmt->close();
} else {
    die($course_id);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Portal</title>
  <link rel="icon" href="./logogub.png" type="image/x-icon">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="students_room.css">
</head>

<body>
  <div id="sidebar" class="bg-dark">
    <ul class="list-unstyled components">
      <li><a href="#" class="sidebar-item">Home</a></li>
      <li><a class="teacher-room sidebar-item">Teacher's Room</a></li>
      <li><a class="student-corner sidebar-item">Student's Corner</a></li>
      <li>
        <form action="/logout" method="POST">
          <input class="btn w-100 btn-primary mt-3" type="submit" value="Logout">
        </form>
      </li>
    </ul>
  </div>
  <div id="content" class="col">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <button type="button" id="sidebarCollapse" class="btn btn-info">
        <i class="navbar-toggler-icon"></i>
      </button>
      <img class="logo p-2" src="./logogub.png" alt="Coming">
      <h2 class="p-3">Green University of Bangladesh</h2>
    </nav>
    <div class="chatbox mt-4">
      <div class="chatbox-messages" id="chatbox-messages">
        <?php foreach ($messages as $message): ?>
          <div class="message <?= $message['is_sender'] ? 'message-sent' : 'message-received' ?>">
            <img class="head-symbol" src="student.png" alt="Sender">
            <span><?= htmlspecialchars($message['sender']) ?>: <?= htmlspecialchars($message['content']) ?> (<?= htmlspecialchars($message['sent_at']) ?>)</span>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="chatbox-input">
        <div class="input-group">
          <input type="text" class="inbox form-control" id="chat-input" placeholder="Type message for <?= htmlspecialchars($course_name) ?>">
          <div class="input-group-append">
            <button class="btn btn-primary" id="send-btn" type="button">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="students_room.js"></script>
</body>

</html>
