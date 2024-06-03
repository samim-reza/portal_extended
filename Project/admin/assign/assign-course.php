<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you're receiving form data via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required form fields are present
    if (isset($_POST['id'], $_POST['course'], $_POST['section'], $_POST['role'])) {
        // Retrieve form data
        $student_id = $_POST['id']; // Assuming 'id' corresponds to 'student_id'
        $course_name = $_POST['course'];
        $course_section = $_POST['section'];
        $role = $_POST['role'];

        // Database connection parameters
        $servername = "localhost"; // Change this to your MySQL server name if it's different
        $db_username = "root"; // Change this to your MySQL username
        $db_password = "S@mim101"; // Change this to your MySQL password
        $database = "portal_extended";

        // Connect to the database
        $conn = new mysqli($servername, $db_username, $db_password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the student exists in the Users table
        $check_user_query = "SELECT user_id FROM Users WHERE student_id = ?";
        $stmt = $conn->prepare($check_user_query);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            echo "Student with ID $student_id does not exist in the database";
            exit; // Stop further execution
        }

        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        // Get the course_id from the Courses table
        $get_course_id_query = "SELECT course_id FROM Courses WHERE course_name = ? AND course_section = ? LIMIT 1";
        $stmt = $conn->prepare($get_course_id_query);
        $stmt->bind_param("ss", $course_name, $course_section);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            echo "Course with name '$course_name' and section '$course_section' does not exist in the database";
            exit; // Stop further execution
        }

        $stmt->bind_result($course_id);
        $stmt->fetch();
        $stmt->close();

        // Insert data into the CourseParticipants table
        $insert_query = "INSERT INTO CourseParticipants (course_id, user_id, student_id, role, joined_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiis", $course_id, $user_id, $student_id, $role);

        if ($stmt->execute()) {
            echo "Course assigned successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Check if there is a chat room for this course or not
        $check_chatroom_query = "SELECT chat_room_id FROM ChatRooms WHERE course_id = ?";
        $stmt = $conn->prepare($check_chatroom_query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->store_result();

        // If no chat room exists for this course, create one
        if ($stmt->num_rows == 0) {
            $stmt->close();
            $insert_chatroom_query = "INSERT INTO ChatRooms (course_id, name, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
            $stmt = $conn->prepare($insert_chatroom_query);
            $stmt->bind_param("is", $course_id, $course_name);

            if ($stmt->execute()) {
                echo "Chat room created successfully";
            } else {
                echo "Error creating chat room: " . $stmt->error;
            }
        }

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Required form fields are missing";
    }
}
?>
