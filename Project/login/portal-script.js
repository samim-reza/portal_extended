document.addEventListener('DOMContentLoaded', function () {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
    });

    fetchCourses();

    function fetchCourses() {
        $.ajax({
            type: "GET",
            url: "portal.php",
            success: function (response) {
                console.log("Response from server:", response); // Log the response
                try {
                    const courses = JSON.parse(response);
                    console.log("Parsed courses:", courses); // Log the parsed courses
                    if (Array.isArray(courses)) {
                        populateDropdown(courses);
                    } else {
                        console.error("Courses is not an array:", courses);
                    }
                } catch (error) {
                    console.error("Error parsing JSON response:", error);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching courses:", xhr.responseText);
            }
        });
    }
    
    function populateDropdown(courses) {
        console.log("Courses:", courses); // Log the courses received
        const dropdownMenu = document.querySelector('#tscDropdown + .dropdown-menu');
        dropdownMenu.innerHTML = ''; // Clear existing items

        var urlParams = new URLSearchParams(window.location.search);
        var studentId = urlParams.get('studentid');
        courses.forEach(course => {
            const a = document.createElement('a');
            a.className = 'dropdown-item';
            a.href = `/Project/faculty_room/faculty_room.php?course_id=${course.course_id}&studentid=${studentId}`;
            // console.log(course.course_id);
            // console.log(studentId);
            a.textContent = course.course_name;
            dropdownMenu.appendChild(a);
        });
    }
});
