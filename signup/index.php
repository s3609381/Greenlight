<!DOCTYPE html>

<?php
  include("../config.php");
  session_start();

if(isset($_POST['signup'])){   
  $username = $password = $confPassword = $firstName = $lastName = $email = '';
  $errMsg = $dupeMsg = '';
	  
  foreach($_POST as $key => $value){
    if(!empty($value)){
      $value = trim($value);
      if($key == 'Email'){
        if(filter_var($value, FILTER_VALIDATE_EMAIL)){
          $email = $value;
          continue;
        }
        else{
          $email = $value;
          $errMsg .= 'Invalid email address.\n';
          continue;
        }
      }
      else if($key == 'username'){
        if(!preg_match("/^[a-zA-Z0-9äöüÄÖÜ!#]+$/", $value)){
          $username = $value;
          $errMsg .= 'Username must only contain letters and numbers.\n';
          continue;
        }
        else{
          $username = $value;
          continue;
        }
      }
      else if($key == 'password'){
        if(strlen($value) < 8){
          $errMsg .= 'Password must be at least 8 characters long and only contain letters, numbers, !, and #.\n';
        }
        else if(!preg_match("/^[a-zA-Z0-9äöüÄÖÜ!#]+$/", $value)){
          $errMsg .= 'Password must only contain letters, numbers, !, and #.\n';
          continue;
        }
        else{
          $password = $value;
          continue;
        }
      }
      else if($key == 'confPassword'){
        if($value <> $_POST['password']){
          $errMsg .= 'Passwords must match.\n';
          continue;
        }
        else{
          $confPassword = $value;
          continue;
        }
      }
      else if($key == 'firstName'){
        if(!preg_match("/^[a-zA-ZäöüÄÖÜ]+$/", $value)){
          $firstName = $value;
          $errMsg .= 'First name must contain only letters.\n';
          continue;
        }
        else{
          $firstName = $value;
          continue;
        }
      }
      else if($key == 'lastName'){
        if(!preg_match("/^[a-zA-ZäöüÄÖÜ]+$/", $value)){
          $lastName = $value;
          $errMsg .= 'Last name must contain only letters.\n';
          continue;
        }
        else{
          $lastName = $value;
          continue;
        }
      }
    }
    else{
      $errMsg = '';
      $errMsg = 'All fields must be filled out.\n';
      echo "<script type='text/javascript'>alert('$errMsg');</script>";
    }
  }

  if($errMsg == ''){
    $lastId = $db->prepare("SELECT UserID FROM tblUsers ORDER BY UserID DESC LIMIT 1");
    $lastId->execute();
    
		$record = $db->prepare("INSERT INTO tblUsers(Email, Password, UserName, LastLogin, SignUpDate) VALUES (:email, :password, :username, NOW(), NOW())");
		$record->bindParam(':email', $email);
		$record->bindParam(':username', $username);
		$record->bindParam(':password', $password);
		
		try{
		  $record->execute();
		  
		  try{
  		  $result = $db->lastInsertId();
  		  
  		  if($result > 0 and $result <> null){
    			$_SESSION['user_id'] = $result;
    			$_SESSION['logged_in'] = FALSE;
    			
    			$details = $db->prepare("INSERT INTO tblUserDetailsSettings(UserID, EmailAuthorised, FirstName, LastName, DefaultLightColourID, AllowSocialMediaPosting, MobileNumber, AllowGPSLocation) 
    			VALUES (:userId, FALSE, :firstName, :lastName, 1, FALSE, null, FALSE)");
    			$details->bindParam(':userId', $result);
    			$details->bindParam(':firstName', $firstName);
    			$details->bindParam(':lastName', $lastName);
    			
    			try{
    			  $details->execute();
    			
    			  header('location:../dashboard');
    			  exit;
    			}
    			catch(PDOException $e){
    			  $errMsg = 'Something went wrong: ' + $e->getCode();
    			  echo "<script type='text/javascript'>alert('$errMsg');</script>";
    			}
    			catch(Exception $e){
    			  $errMsg = 'Something went wrong: ' + $e->getCode();
    			  echo "<script type='text/javascript'>alert('$errMsg');</script>";
    			}
    			
          $username = $password = $confPassword = $firstName = $lastName = $email = '';
    		}
    		else{
          $username = $password = $confPassword = $firstName = $lastName = $email = '';
          $errMsg = 'An unknown error has occurred. Please try again.';
    			echo "<script type='text/javascript'>alert('$errMsg');</script>";
    		}
  		}
  		catch(PDOException $e){
        $errMsg = 'Something went wrong: ' + $e->getCode();
  			echo "<script type='text/javascript'>alert('$errMsg');</script>";
  		}
  		catch(Exception $e){
        $errMsg = 'Something went wrong: ' + $e->getCode();
  		  echo "<script type='text/javascript'>alert('$errMsg');</script>";
  		}
		}
		catch(PDOException $e){
		  if($e->getCode() == 1062){
		    $dupeMsg = 'Username already taken.';
		  }
		  else{
		    $errMsg = 'Something went wrong: ' + $e->getCode();
			  echo "<script type='text/javascript'>alert('$errMsg');</script>";
		  }
		}
		catch(Exception $e){
      $errMsg = 'Something went wrong: ' + $e->getCode();
		  echo "<script type='text/javascript'>alert('$errMsg');</script>";
		}
  }
  else{
    echo "<script type='text/javascript'>alert('$errMsg');</script>";
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

  <!-- favicons -->
  <link rel="shortcut icon" href="/images/favicon/favicon.ico">
  <link rel="icon" sizes="16x16 32x32 64x64" href="/images/favicon/favicon.ico">
  <link rel="icon" type="image/png" sizes="196x196" href="/images/favicon/favicon-192.png">
  <link rel="icon" type="image/png" sizes="160x160" href="/images/favicon/favicon-160.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96.png">
  <link rel="icon" type="image/png" sizes="64x64" href="/images/favicon/favicon-64.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16.png">
  <link rel="apple-touch-icon" href="/images/favicon/favicon-57.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/favicon-114.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/favicon-72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/favicon-144.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/favicon-60.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/favicon-120.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/favicon-76.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/favicon-152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/favicon-180.png">
  <meta name="msapplication-TileColor" content="#FFFFFF">
  <meta name="msapplication-TileImage" content="/images/favicon/favicon-144.png">
  <meta name="msapplication-config" content="/browserconfig.xml">

  <title>Greenlight - Sign Up</title>

  <!-- css -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">

  <!-- js / jquery -->
  <script src="../js/jquery-2.2.4.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>

</head>

<body>
  <!-- nav bar + header -->
  
  <?php include("../modules/nav.php"); ?>

  <!-- content and footer -->
  <div class="container">

      <div class="row">
        <div class="col-lg-12">

          <h1 class="page-header">
          Sign Up
        </h1>
        </div>
        <div class="col-md-6">
          <div class="panel panel-no-border">
            <div class="panel-body-signup">

              <form action="" method="post" class="form-horizontal">
                <div class="form-group">
                  <label for="inputEmail" class="col-sm-4 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="inputLastName" placeholder="Email" name="Email" value="<?php if(isset($email)) {echo $email;} ?>" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputUsername" class="col-sm-4 control-label">Username</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" value="<?php if(isset($username)) {echo $username;} ?>" required>
                  </div>
                  <?php
                    echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$dupeMsg.'</div>';
                  ?>
                </div>
                
                <div class="form-group">
                  <label for="inputPassword" class="col-sm-4 control-label">Password</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputPassword" class="col-sm-4 control-label">Verify Password</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Re-Confirm Password" name="confPassword" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-4 control-label">First Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputFirstName" placeholder="First Name" name="firstName" value="<?php if(isset($firstName)) {echo $firstName;} ?>" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputLastName" class="col-sm-4 control-label">Last Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputLastName" placeholder="Last Name" name="lastName" value="<?php if(isset($lastName)) {echo $lastName;} ?>" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-4 col-sm-10">
                    <input class="btn btn-success" type="submit" name='signup' value="Sign Up" />
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
      <hr>

      <!-- footer -->
      <?php include("../modules/footer.php") ?>

  </div>
  <!-- /contatiner -->
</body>

</html>
