<!DOCTYPE html>

<?php

// redirect already logged in users back to the dashboard if they somehow end up back on the login page while logged in.
if(isset($_SESSION['user_name'])){ 
    header("Location: ../dashboard");
  }
   include("../config.php");
   session_start();
   
 if(isset($_POST['submit'])){
		$errMsg = '';
		//username and password sent from Form
		$username = trim($_POST['username']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		if($username == '')
			$errMsg .= 'You must enter your Username<br>';
		if($password == '')
			$errMsg .= 'You must enter your Password<br>';
		if($errMsg == ''){
			$records = $db->prepare("SELECT * FROM tblUsers WHERE (Email = :email OR UserName = :username) AND Password = :password AND Active = 1");
			$records->bindParam(':email', $username);
			$records->bindParam(':username', $username);
			$records->bindParam(':password', $password);
			$records->execute();
			$results = $records->fetch(PDO::FETCH_ASSOC);
			if($results > 0){
			  
			  // set last login as the current time
			  $lastLogin= $db->prepare("UPDATE tblUsers SET LastLogin= NOW() WHERE UserID=:userID");
			  $lastLogin->bindParam(':userID', $results['UserID']);
			  $lastLogin->execute();
			  
				$_SESSION['user_name'] = $results['UserName'];
				$_SESSION['user_id'] = $results['UserID'];
				$_SESSION['logged_in'] = TRUE;
				
				header('location:../dashboard');
				exit;
			}else{
				$errMsg .= 'Incorrect username or password.<br>';
			}
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

  <title>Greenlight - Login</title>

  <!-- css -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/style.css" rel="stylesheet">

  <!-- js / jquery -->
  <script src="/js/jquery-2.2.4.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>

</head>

<body>
  <!-- nav bar + header -->
  <?php
  
  if(!isset($_SESSION['user_name'])){ 
    include("../modules/nav.php");
  }
  else{
    include("../modules/nav-loggedin.php");
  }
  
  ?>

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
          Login
        </h1>
        </div>
        <div class="col-md-6">
          <div class="panel panel-no-border">
            <div class="panel-body">

              <form action="" method="post" class="form-horizontal">
                <div class="form-group">
                  <label for="inputUsername" class="col-sm-5 control-label">Username / Email</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputPassword" class="col-sm-5 control-label">Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password" required>
                  </div>
                </div>
                
                
                <!-- remove comment for check box
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">

                  <label>
                    <input type="checkbox"> Remember me
                  </label>

                    </div>
                  </div>
                </div>
                 -->
                 
                <div class="form-group">
                  <div class="col-sm-offset-5 col-sm-10">
                    <input class="btn btn-success" type="submit" name='submit' value="Login" />
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