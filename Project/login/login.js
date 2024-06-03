function validateForm() {
    console.log("Validating form...");
    var studentid = document.getElementById("student-id").value;
    var password = document.getElementById("password").value;

    if (studentid === "" || password === "") {
        alert("ID and password are required.");
        return false;
    } else {
        //  var link = 'portal.html?studentid=' + studentid;
        //     $('.login').attr('href', link),
        $.ajax({
            type: "POST",
            url: "login.php",
            data: {
                id: studentid,
                password: password
            },
            success: function (response) {
                //alert(response);
                if (response === "success") {
                    window.location.href = "portal.html?studentid=" + studentid;
                } else {
                    alert("Invalid ID or password.");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
}