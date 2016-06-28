<!DOCTYPE html>

<?php
   include("config.php");
   session_start();

 if(isset($_POST['submit'])){
		$errMsg = '';
		//username and password sent from Form
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		if($username == '')
			$errMsg .= 'You must enter your Username<br>';

		if($password == '')
			$errMsg .= 'You must enter your Password<br>';


		if($errMsg == ''){
			$records = $db->prepare('SELECT * FROM users WHERE username = :demo AND password =:demo');
			$records->bindParam(':demo', $username);
			$records->bindParam(':demo', $password);
			$records->execute();
			$results = $records->fetch(PDO::FETCH_ASSOC);
			if($results > 0){
				$_SESSION['login_success'] = $results['username'];
				header('location:dashboard.php');
				exit;
			}else{
				$errMsg .= 'Username and Password are not found<br>';
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

  <!-- icons (.png for apple and .ico for favicon) -->
  <link rel="apple-touch-icon" href="">
  <link rel="shortcut icon" href="">

  <title>Greenlight</title>

  <!-- css -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <!-- js / jquery -->
  <script src="js/jquery-2.2.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

</head>

<body>
  <!-- nav bar + header -->
  <?php include("modules/nav.php") ?>

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
                <label for="inputUsername" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" required>
                </div>
              </div>
              <div class="form-group">
                <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password" required>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">

                    <!-- remove comment for check box

                    <label>
                    <input type="checkbox"> Remember me
                  </label>

                -->

              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input class ="btn btn-success" type="submit" name='submit' value="Login" />
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

</div><!-- /contatiner -->
</body>

</html>
