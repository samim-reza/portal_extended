# Portal Extended - University Course Management System

[![University Project](https://img.shields.io/badge/Project-University%20Course-blue)]()
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple)]()
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)]()
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-yellow)]()

## 📖 Project Overview

**Portal Extended** is an advanced university course management and communication system developed as an extended version of the standard university portal. This project was created for a university course to enhance student-teacher communication through automated course-based chat rooms.

The system automatically manages chat groups for each course, enabling seamless communication between students and faculty while maintaining proper access controls and permissions.

## ✨ Key Features

### 🚀 Automated Chat Room Management
- **Automatic Group Creation**: When a new course starts at the beginning of a semester, message groups are automatically created
- **Auto-enrollment**: Students are automatically added to course groups when enrolled in a course
- **Semester-based Lifecycle**: Groups are automatically deleted when the course ends at semester completion

### 💬 Dual Room System
Each course has **two distinct communication rooms**:

#### 1. **Student's Corner** (Students-Only Room)
- Exclusive space for students
- No teacher access
- Open communication among peers
- Perfect for study groups and peer discussions
- All students can freely send messages

#### 2. **Teacher's Room** (Faculty-Supervised Room)
- Includes all students and the course teacher
- **Restricted messaging privileges**:
  - Only the teacher can send messages
  - Only the CR (Class Representative) assigned by the teacher can send messages
  - Other students can only view messages
- Ideal for official announcements and course information

### 👥 User Management
- **Multi-role Support**: Admin, Teacher, Student, and Class Representative (CR)
- **User Creation**: Admin panel for creating new users
- **Course Assignment**: Assign students and teachers to courses
- **Role-based Access Control**: Different permissions based on user roles

### 🔐 Authentication & Security
- Secure login system with password hashing
- Session management
- Role-based access restrictions
- SQL injection prevention with prepared statements

## 🏗️ System Architecture

### Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **UI Framework**: Bootstrap 4.5.2
- **AJAX**: jQuery for asynchronous operations

### Database Schema

The system uses the following main tables:

#### **Users**
- `user_id` (Primary Key)
- `student_id` (Unique)
- `username`
- `email`
- `password_hash`
- `role` (student/teacher/admin/cr)

#### **Courses**
- `course_id` (Primary Key)
- `course_name`
- `course_section`
- `created_at`

#### **CourseParticipants**
- `participant_id` (Primary Key)
- `course_id` (Foreign Key)
- `user_id` (Foreign Key)
- `student_id`
- `role`
- `joined_at`

#### **ChatRooms**
- `chat_room_id` (Primary Key)
- `course_id` (Foreign Key)
- `name`
- `created_at`

#### **Messages**
- `message_id` (Primary Key)
- `chat_room_id` (Foreign Key)
- `sender_id` (Foreign Key)
- `content`
- `chat_room` (1: Teacher's Room, 2: Student's Corner)
- `sent_at`

## 📂 Project Structure

```
portal_extended/
├── index.html                      # Landing page
├── info.php                        # System information
├── images/                         # Image assets
├── Project/
│   ├── selection.html              # Course selection interface
│   ├── select-style.css           # Selection page styles
│   ├── admin/                     # Admin module
│   │   ├── admin/                 # Admin dashboard
│   │   │   ├── admin.html         # Admin interface
│   │   │   ├── admin.php          # Admin backend
│   │   │   ├── login.html         # Admin login
│   │   │   ├── login.php          # Admin authentication
│   │   │   └── *.css, *.js        # Styles and scripts
│   │   ├── create/                # User creation module
│   │   │   ├── createUser.html    # User creation form
│   │   │   ├── create-user.php    # User creation handler
│   │   │   └── *.css, *.js        # Styles and scripts
│   │   └── assign/                # Course assignment module
│   │       ├── assignCourse.html  # Assignment form
│   │       ├── assign-course.php  # Assignment handler
│   │       └── *.css, *.js        # Styles and scripts
│   ├── login/                     # Student/Teacher login
│   │   ├── portal.html            # Main portal interface
│   │   ├── portal.php             # Portal backend
│   │   ├── login.html             # Login page
│   │   ├── login.php              # Authentication
│   │   ├── logout.php             # Session termination
│   │   └── *.css, *.js            # Styles and scripts
│   ├── faculty_room/              # Teacher's Room
│   │   ├── faculty_room.php       # Main room interface
│   │   ├── send_message.php       # Message handler
│   │   ├── get_messages.php       # Message retrieval
│   │   ├── check_role.php         # Permission verification
│   │   ├── logout.php             # Logout handler
│   │   └── *.css, *.js            # Styles and scripts
│   └── students_room/             # Student's Corner
│       ├── students_room.php      # Main room interface
│       ├── send_message.php       # Message handler
│       ├── get_messages.php       # Message retrieval
│       ├── logout.php             # Logout handler
│       └── *.css, *.js            # Styles and scripts
└── README.md                       # Project documentation
```

## 🚀 Installation & Setup

### Prerequisites
- Apache2 or Nginx web server
- PHP 7.4 or higher
- MySQL 8.0 or higher
- Web browser (Chrome, Firefox, Safari, etc.)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/samim-reza/portal_extended.git
   cd portal_extended
   ```

2. **Database Setup**
   ```sql
   -- Create database
   CREATE DATABASE portal_extended;
   USE portal_extended;

   -- Create Users table
   CREATE TABLE Users (
       user_id INT AUTO_INCREMENT PRIMARY KEY,
       student_id INT UNIQUE NOT NULL,
       username VARCHAR(100) NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       password_hash VARCHAR(255) NOT NULL,
       role ENUM('student', 'teacher', 'admin', 'cr') NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   -- Create Courses table
   CREATE TABLE Courses (
       course_id INT AUTO_INCREMENT PRIMARY KEY,
       course_name VARCHAR(100) NOT NULL,
       course_section VARCHAR(10),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   -- Create CourseParticipants table
   CREATE TABLE CourseParticipants (
       participant_id INT AUTO_INCREMENT PRIMARY KEY,
       course_id INT NOT NULL,
       user_id INT NOT NULL,
       student_id INT NOT NULL,
       role VARCHAR(20) NOT NULL,
       joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE,
       FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
   );

   -- Create ChatRooms table
   CREATE TABLE ChatRooms (
       chat_room_id INT AUTO_INCREMENT PRIMARY KEY,
       course_id INT NOT NULL,
       name VARCHAR(100) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE
   );

   -- Create Messages table
   CREATE TABLE Messages (
       message_id INT AUTO_INCREMENT PRIMARY KEY,
       chat_room_id INT NOT NULL,
       sender_id INT NOT NULL,
       content TEXT NOT NULL,
       chat_room INT NOT NULL COMMENT '1: Teacher Room, 2: Student Corner',
       sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (chat_room_id) REFERENCES ChatRooms(chat_room_id) ON DELETE CASCADE,
       FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE
   );
   ```

3. **Configure Database Connection**
   
   Update the database credentials in all PHP files:
   ```php
   $servername = "localhost";
   $db_username = "your_username";    // Change this
   $db_password = "your_password";    // Change this
   $database = "portal_extended";
   ```

   Files to update:
   - `Project/admin/create/create-user.php`
   - `Project/admin/assign/assign-course.php`
   - `Project/login/login.php`
   - `Project/login/portal.php`
   - `Project/faculty_room/faculty_room.php`
   - `Project/faculty_room/send_message.php`
   - `Project/faculty_room/get_messages.php`
   - `Project/students_room/students_room.php`
   - `Project/students_room/send_message.php`
   - `Project/students_room/get_messages.php`

4. **Configure Web Server**

   For Apache (place in `/etc/apache2/sites-available/portal.conf`):
   ```apache
   <VirtualHost *:80>
       ServerName portal.local
       DocumentRoot /path/to/portal_extended
       
       <Directory /path/to/portal_extended>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/portal_error.log
       CustomLog ${APACHE_LOG_DIR}/portal_access.log combined
   </VirtualHost>
   ```

   Enable the site:
   ```bash
   sudo a2ensite portal.conf
   sudo systemctl reload apache2
   ```

5. **Set Permissions**
   ```bash
   sudo chown -R www-data:www-data portal_extended/
   sudo chmod -R 755 portal_extended/
   ```

6. **Access the Application**
   - Navigate to `http://localhost/portal_extended` or your configured domain
   - Default admin access should be created manually in the database

## 📱 Usage Guide

### Admin Workflow

1. **Login as Admin**
   - Access admin login page at `/Project/admin/admin/login.html`

2. **Create Users**
   - Navigate to "Create User" section
   - Fill in: ID, Name, Email, Password, Role (student/teacher/cr)
   - Click "Create User"

3. **Create Courses**
   - Add courses directly to the database or create a course management interface

4. **Assign Courses**
   - Navigate to "Assign Course" section
   - Enter: Student ID, Course Name, Section, Role
   - System automatically creates chat rooms if they don't exist
   - Student is added to the course and associated chat rooms

### Student Workflow

1. **Login**
   - Access student login at `/Project/login/login.html`
   - Enter Student ID and Password

2. **View Courses**
   - Dashboard displays all enrolled courses
   - Click on a course to access chat rooms

3. **Communication**
   - **Student's Corner**: Freely chat with classmates
   - **Teacher's Room**: View announcements from teacher and CR

### Teacher Workflow

1. **Login**
   - Use teacher credentials to login

2. **Access Courses**
   - View assigned courses
   - Access Teacher's Room for each course

3. **Send Announcements**
   - Post messages in Teacher's Room
   - Messages visible to all students
   - Assign CR privileges to selected students

### CR (Class Representative) Workflow

1. **Special Privileges**
   - Can send messages in Teacher's Room
   - Acts as liaison between students and teacher
   - All other functionalities same as regular students

## 🔒 Security Features

- **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
- **Prepared Statements**: All database queries use prepared statements to prevent SQL injection
- **Session Management**: Secure session handling with timeout
- **Role-based Access**: Different access levels for different user types
- **Input Validation**: Server-side validation of all user inputs
- **XSS Prevention**: HTML special characters escaped

## 🎯 Key Functionalities

### Automatic Chat Room Management
```php
// When assigning a course, automatically create chat room
$check_chatroom_query = "SELECT chat_room_id FROM ChatRooms WHERE course_id = ?";
if ($stmt->num_rows == 0) {
    $insert_chatroom_query = "INSERT INTO ChatRooms (course_id, name, created_at) 
                              VALUES (?, ?, CURRENT_TIMESTAMP)";
    // Execute insertion
}
```

### Message Filtering by Room Type
```php
// Teacher's Room (chat_room = 1)
$stmt = $conn->prepare("SELECT * FROM Messages 
                        WHERE chat_room_id = ? AND chat_room = 1");

// Student's Corner (chat_room = 2)
$stmt = $conn->prepare("SELECT * FROM Messages 
                        WHERE chat_room_id = ? AND chat_room = 2");
```

### Permission Control
- Teacher's Room: Only teacher and CR can send messages
- Student's Corner: All students can send messages
- Role verification before message submission

## 🔄 Future Enhancements

- [ ] File sharing in chat rooms
- [ ] Real-time messaging with WebSocket
- [ ] Push notifications
- [ ] Course completion automation with semester end dates
- [ ] Advanced admin dashboard with analytics
- [ ] Mobile responsive design improvements
- [ ] Multi-language support
- [ ] Export chat history
- [ ] User profile management
- [ ] Course materials upload/download

## 🐛 Known Issues

- Manual database configuration required in multiple files
- No automatic semester end detection (requires manual group deletion)
- Limited mobile responsiveness
- No real-time message updates (requires page refresh)

## 🤝 Contributing

This is a university course project. Contributions, suggestions, and improvements are welcome!

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is created for educational purposes as part of a university course.

## 👨‍💻 Author

**Samim Reza**
- GitHub: [@samim-reza](https://github.com/samim-reza)

## 🎓 University

**Green University of Bangladesh**

## 📞 Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Contact through university email

## 🙏 Acknowledgments

- Green University of Bangladesh for the course opportunity
- Course instructors for guidance
- Fellow students for testing and feedback

---

**Note**: This project was developed as an extended version of the standard university portal to enhance student-teacher communication and course management. It demonstrates practical implementation of web technologies, database management, and user authentication systems.

## 📸 Screenshots

*Add screenshots of your application here to showcase the UI/UX*

---

### Quick Start Commands

```bash
# Clone repository
git clone https://github.com/samim-reza/portal_extended.git

# Navigate to project
cd portal_extended

# Import database
mysql -u root -p < database.sql

# Start Apache server
sudo systemctl start apache2

# Access application
# Browser: http://localhost/portal_extended
```

---

**Made with ❤️ for Green University of Bangladesh**
