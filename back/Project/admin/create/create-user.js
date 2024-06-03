function createUser() {
    const id = document.getElementById('id').value;
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;
  
    $.ajax({
      type: "POST",
      url: "create-user.php",
      data: {
        id: id,
        name: name,
        email: email,
        password: password,
        role: role
      },
      success: function(response) {
        console.log(response);
        // alert(response);
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
  