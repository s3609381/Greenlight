<!DOCTYPE html>

<?php
  include("config.php");
  session_start();
   
  $username = $password = $firstName = $lastName = $email = '';
	$usernameErr = $passwordErr = $firstNameErr = $lastNameErr = $emailErr = '';

  if(isset($_POST['submit'])){
  
    if(empty($username) == false){
		  $username = test_input($username);
    }
    
		$email = test_input($email);
		$password = test_input($password);
		$firstName = test_input($firstName);
		$lastName = test_input($lastName);

		function test_input($data){
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	}
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Greenlight">
  <meta name="author" content=";DROP TABLE team - RMIT University">

  <!-- icons (.png for apple and .ico for favicon) -->
  <link rel="apple-touch-icon" href="">
  <link rel="shortcut icon" href="">

  <title>Greenlight - Sign Up</title>

  <!-- css -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <!-- js / jquery -->
  <script src="js/jquery-2.2.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

</head>

<body>
  <!-- nav bar + header -->
  
  <?php include("modules/nav.php"); ?>

  <!-- content and footer -->
  <div class="container">

    <?php
      if(isset($errMsg)){
        echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$errMsg.'</div>';
      }
    ?>

      <div class="row">
        <div class="col-lg-12">

          <h1 class="page-header">
          Sign-Up
        </h1>
        </div>
        <div class="col-md-6">
          <div class="panel panel-no-border">
            <div class="panel-body">

              <form action="" method="post" class="form-horizontal">
                <div class="form-group">
                  <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputLastName" placeholder="Email" name="Email" required>
                  </div>
                  <?php
                    if(isset($emailErr)){
                      echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$emailErr.'</div>';
                    }
                  ?>
                </div>
                
                <div class="form-group">
                  <label for="inputUsername" class="col-sm-2 control-label">Username</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username">
                  </div>
                  <?php
                    if(isset($usernameErr)){
                      echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$usernameErr.'</div>';
                    }
                  ?>
                </div>
                
                <div class="form-group">
                  <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password" required>
                  </div>
                  <?php
                    if(isset($passwordErr)){
                      echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$passwordErr.'</div>';
                    }
                  ?>
                </div>
                
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputFirstName" placeholder="First Name" name="firstName" required>
                  </div>
                  <?php
                    if(isset($firstNameErr)){
                      echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$firstNameErr.'</div>';
                    }
                  ?>
                </div>
                
                <div class="form-group">
                  <label for="inputLastName" class="col-sm-2 control-label">Last Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputLastName" placeholder="Last Name" name="lastName" required>
                  </div>
                  <?php
                    if(isset($lastNameErr)){
                      echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$lastNameErr.'</div>';
                    }
                  ?>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-success" type="submit" name='signup' value="Signup" />
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
      <hr>

      <!-- footer -->
      <?php include("modules/footer.php") ?>

  </div>
  <!-- /contatiner -->
</body>

</html>
