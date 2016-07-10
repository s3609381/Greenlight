<!DOCTYPE html>

<?php
session_start();
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
  
  <title>Greenlight - Dashboard - Create Basic Light</title>
  
  <!-- css -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  
  <!-- js / jquery -->
  <script src="../js/jquery-2.2.4.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/dash-nav-js.js"></script>
  
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
    
    <div class="row">
        
      <div class="col-lg-12">
        <h1 class="page-header">
          <?php echo "Create Basic Light"; ?>
        </h1>
      </div>
      
      <?php include("../modules/dash-nav.php"); ?>
      
      <div class="col-md-10">
        <div class="panel panel-default">
          <div class="panel-body">
            <p>Lights</p>
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
