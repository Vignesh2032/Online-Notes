// Once the Sign up form is Submitted.

$('#signupform').submit(function(event){
    event.preventDefault();

    //collect the user inputs
    var datatopost = $(this).serializeArray();

    $.ajax({
        url: "signup.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data){
                $('#signupmessage').html(data);
            }
        },
        error: function(){
            $('#signupmessage').html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again later.</div>");
        }
    });
});

// Once the Login form is Submitted.

$('#loginform').submit(function (event) {
    event.preventDefault();

    //Collect the user inputs
    var datatopost = $(this).serializeArray();
    //console.log(datatopost);
    $.ajax({
        url: 'login.php',
        type: "POST",
        data: datatopost,
        success: function (data) {
            if (data == "success") {
                window.location = "mainpageloggedin.php";
            }
            else {
                $('#loginmessage').html(data);
            }
        },
        error: function () {
            $('#loginmessage').html("<div class= 'alert alert-danger'>There was an error with the Ajax call. Please try again later.</div>");
        }
    });
});

// Once the Forgot Password form is Submitted.

$('#forgotpasswordform').submit(function (event) {
    event.preventDefault();

    //Collect the user inputs
    var datatopost = $(this).serializeArray();
    //console.log(datatopost);
    $.ajax({
        url: 'forgot-password.php',
        type: "POST",
        data: datatopost,
        success: function (data) {
            
            $('#forgotpasswordmessage').html(data);
        },
        error: function () {
            $('#forgotpasswordmessage').html("<div class= 'alert alert-danger'>There was an error with the Ajax call. Please try again later.</div>");
        }
    });
});