function assignCourse() {
    const id = document.getElementById('id').value;
    const course = document.getElementById('course').value;
    const section = document.getElementById('section').value;
    const role = document.getElementById('role').value;
  
    $.ajax({
      type: "POST",
      url: "assign-course.php",
      data: {
        id: id,
        course: course,
        section: section,
        role: role
      },
      success: function(response) {
        // alert(response);
        console.log(response);
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
  