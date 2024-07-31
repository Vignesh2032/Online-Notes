<?php
	session_start();
	include('connection.php');
	
	//logout
	include('logout.php');
	
	//remember me
	include('remember.php'); 
	
	//if the user select the rememberme check box while login -> allow the user to direct login
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Notes</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="styling.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <!--Navigation Bar-->  
      <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
      
          <div class="container-fluid">
              <div class="navbar-header">
              
                  <a class="navbar-brand">Online Notes</a>
                  <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
              </div>
              <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Contact us</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#loginModal" data-toggle="modal">Login</a></li>
                </ul>
              </div>
          </div>
      </nav>
            <!--Sign up Button-->
      <div class="jumbotron" id="myContainer">
          <h1>Online Notes App</h1>
          <p>Your Notes with you wherever you go.</p>
          <p>Easy to use, protects all your notes!</p>
          <button type="button" class="btn btn-lg green signup" data-target="#signupModal" data-toggle="modal">Sign up-It's free</button>
      </div>
            <!--Login Modal-->
      <form method="post" id="loginform">
          <div class="modal" id="loginModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button class="close" data-dismiss="modal">&times;</button>
                          <h4 id="myModalLabel">Login:</h4>
                      </div>
                      <div class="modal-body">
                          <div id="loginmessage"></div>

                          <div class="form-group">
                              <label for="loginemail" class="sr-only">Email:</label>
                              <input class="form-control" type="email" id="loginemail" name="loginemail" placeholder="Email" maxlength="50">
                          </div>

                          <div class="form-group">
                              <label for="loginpassword" class="sr-only">Password:</label>
                              <input class="form-control" type="password" id="loginpassword" name="loginpassword" placeholder="Password" maxlength="30">
                          </div>
                          
                          <div class="checkbox">
                              <label><input type="checkbox" name="rememberme" id="rememberme">Remember me</label>
                              <a class="pull-right" style="cursor:pointer" data-dismiss="modal" data-target="#forgetpasswordModal" data-toggle="modal">Forgot Password?</a>
                          </div>

                      </div>
                      <div class="modal-footer">
                          <input class="btn green" type="submit" name="login" value="Login">
                          <button type="button" class="btn " data-dismiss="modal">Cancel</button>
                          <button type="button" class="btn  pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
                      </div>
                  </div>

              </div>

          </div>
      </form>

          <!--Sign up Modal-->
      <form method="post" id="signupform">
          <div class="modal" id="signupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button class="close" data-dismiss="modal">&times;</button>
                          <h4 id="myModalLabel">Sign Up today and Start using our Online Notes App!</h4>
                      </div>
                      <div class="modal-body">
                          <div id="signupmessage"></div>
                          <div class="form-group">
                              <label for="username" class="sr-only">Username:</label>
                              <input class="form-control" type="text" id="username" name="username" placeholder="Username" maxlength="30">
                          </div>

                          <div class="form-group">
                              <label for="email" class="sr-only">Email:</label>
                              <input class="form-control" type="email" id="email" name="email" placeholder="Email Address" maxlength="50">
                          </div>

                          <div class="form-group">
                              <label for="password" class="sr-only">Choose a Password:</label>
                              <input class="form-control" type="password" id="password" name="password" placeholder="Choose a password" maxlength="30">
                          </div>

                          <div class="form-group">
                              <label for="password2" class="sr-only">Confirm password:</label>
                              <input class="form-control" type="password" id="password2" name="password2" placeholder="Confirm password" maxlength="30">
                          </div>
                          
                      </div>
                      <div class="modal-footer">
                          <input class="btn green" type="submit" name="signup" value="Sign up">
                          <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                      </div>
                  </div>

              </div>

          </div>
      </form>

                <!--Forgot Password Modal-->
            <form method="post" id="forgotpasswordform">
          <div class="modal" id="forgetpasswordModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button class="close" data-dismiss="modal">&times;</button>
                          <h4 id="myModalLabel">Forgot Password? Enter your email address:</h4>
                      </div>
                      <div class="modal-body">
                          <div id="forgotpasswordmessage"></div>

                          <div class="form-group">
                              <label for="forgotemail" class="sr-only">Email:</label>
                              <input class="form-control" type="email" id="forgotemail" name="forgotemail" placeholder="Email" maxlength="50">
                          </div>
                      </div>
                      <div class="modal-footer">
                          <input class="btn green" type="submit" name="forgotpasswordy" value="Submit">
                          <button type="button" class="btn " data-dismiss="modal">Cancel</button>
                          <button type="button" class="btn  pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
                      </div>
                  </div>

              </div>

          </div>
      </form>

      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="index.js"></script>
  </body>
</html>