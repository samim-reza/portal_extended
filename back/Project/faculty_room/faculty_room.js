$(document).ready(function() {
  $('#sidebarCollapse').on('click', function() {
    $('#sidebar').toggleClass('active');
    $('#content').toggleClass('active');
  });

  function addMessage(message, type) {
    var messageElement = $('<div>').addClass('message').addClass(type);
    var headSymbol = $('<img>').addClass('head-symbol').attr('src', type === 'message-sent' ? '/Project/students_room/student.png' : 'faculty.png');
    var messageText = $('<span>').text(message);

    messageElement.append(headSymbol).append(messageText);
    $('#chatbox-messages').append(messageElement);
    $('#chatbox-messages').scrollTop($('#chatbox-messages')[0].scrollHeight);
  }

  $('#send-btn').click(function() {
    var message = $('#chat-input').val();
    if (message.trim() !== '') {
      addMessage(message, 'message-sent');
      $('#chat-input').val('');

      setTimeout(function() {
        addMessage('Received: ' + message, 'message-received');
      }, 1000);
    }
  });

  $('#chat-input').keypress(function(e) {
    if (e.which === 13) {
      $('#send-btn').click();
    }
  });
  var urlParams = new URLSearchParams(window.location.search);
  var courseId = urlParams.get('course_id');
  var studentId = urlParams.get('studentid');
  var studentpage = ' /Project/students_room/students_room.php?course_id=' + courseId + '&studentid=' + studentId;
  $('.student-corner').attr('href', studentpage);
var teacherpage = `/Project/faculty_room/faculty_room.php?course_id=${courseId}&studentid=${studentId}`;
  $('.teacher-room').attr('href', teacherpage);

  $.ajax({
    url: `/Project/faculty_room/check_role.php?course_id=${courseId}&student_id=${studentId}`,
    method: 'GET',
    success: function(response) {
      // console.log(response);  
      if (response === 'yes') {
        console.log(response);
        $('#send-btn').prop('disabled', false);
      } else {
        console.log(response);
        $('#send-btn').prop('disabled', true);
      }
    },
    error: function(error) {
      console.error('Error:', error);
    }
  });
  
});
