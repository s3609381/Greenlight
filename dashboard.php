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
          <?php echo $_SESSION['login_success']."'s dashboard"; ?>
        </h1>
      </div>
      <div class="col-md-2">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4>Navigation</h4>
          </div>
          <div class="panel-body">
            <a href="session_dump.php">View Session</a> <br/>
            <a href="logout.php">Sign Out</a>
            
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4>Lights</h4>
          </div>
          <div class="panel-body">
            <p>Lights</p>
            
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4>Subscribed Lights</h4>
          </div>
          <div class="panel-body">
            <p>Lights</p>
            
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
