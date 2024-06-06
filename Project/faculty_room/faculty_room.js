$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
      $('#sidebar').toggleClass('active');
      $('#content').toggleClass('active');
    });
  
    var urlParams = new URLSearchParams(window.location.search);
    var courseId = urlParams.get('course_id');
    var studentId = urlParams.get('studentid');
    var course_id = Number(courseId);
    var senderId = Number(studentId);
    
    function addMessage(message, type) {
      var messageElement = $('<div>').addClass('message').addClass(type);
      var headSymbol = $('<img>').addClass('head-symbol').attr('src', type === 'message-sent' ? 'student.png' : 'faculty.png');
      var messageText = $('<span>').text(message.content);
      var messageTime = $('<span>').addClass('message-time').text(message.sent_at);
  
      messageElement.append(headSymbol).append(messageText).append(messageTime);
      $('#chatbox-messages').append(messageElement);
      $('#chatbox-messages').scrollTop($('#chatbox-messages')[0].scrollHeight);
    }
  
    $('#send-btn').click(function() {
      var message = $('#chat-input').val();
      if (message.trim() === '') return;
  
      $.ajax({
        url: `/Project/faculty_room/send_message.php?chat_room=${1}`,
        method: 'POST',
        data: {
          course_id: course_id,
          sender_id: senderId,
          content: message
        },
        success: function(response) {
          addMessage({ content: message, sent_at: new Date().toLocaleString() }, 'message-sent');
          $('#chat-input').val('');
        },
        error: function(error) {
          console.error('Error:', error);
        }
      });
    });
  
    $('#chat-input').keypress(function(e) {
      if (e.which === 13) {
        $('#send-btn').click();
      }
    });
  
    var studentpage = `/Project/students_room/students_room.php?course_id=${courseId}&studentid=${studentId}`;
    $('.student-corner').attr('href', studentpage);
    var teacherpage = `/Project/faculty_room/faculty_room.php?course_id=${courseId}&studentid=${studentId}`;
    $('.teacher-room').attr('href', teacherpage);
  
    $.ajax({
      url: `/Project/faculty_room/check_role.php?course_id=${courseId}&student_id=${studentId}`,
      method: 'GET',
      success: function(response) {
        if (response === 'yes') {
          $('#send-btn').prop('disabled', false);
        } else {
          $('#send-btn').prop('disabled', true);
        }
      },
      error: function(error) {
        console.error('Error:', error);
      }
    });
  
    // Retrieve and display existing messages
    $.ajax({
      url: `/Project/faculty_room/get_messages.php?chat_room=${1}&course_id=${course_id}`,
      method: 'GET',
      success: function(response) {
        var messages = JSON.parse(response);
        messages.forEach(function(message) {
          var type = message.sender_id == senderId ? 'message-sent' : 'message-received';
          addMessage(message, type);
        });
      },
      error: function(error) {
        console.error('Error:', error);
      }
    });
  });
  