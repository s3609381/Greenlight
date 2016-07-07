<!DOCTYPE html>

<?php
session_start();
if(!isset($_SESSION['login_success'])){ //if login in session is not set
    header("Location: login.php");
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

  <title>Greenlight - Dashboard</title>

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
    <div class="row">
      <div class="col-lg-12">

        <h1 class="page-header">
          <?php echo 'Signed in as '.$_SESSION['login_success']; ?>
        </h1>
      </div>

      <div class="col-md-12">
        <div class="panel panel-no-border">
          <div class="panel-body">
            <a href="session_dump.php">View Session</a> | <a href="logout.php">Sign Out</a>
            
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
