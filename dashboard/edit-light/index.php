<!DOCTYPE html>

<?php
session_start();

if(!isset($_SESSION['user_name'])){ //if login in session is not set
  header("Location: ../../login");
}

if(!isset($_GET['url'])){
    // redirect back to login or dashboard if user tries to access /edit-light directly
    header("Location: ../login");
}

include('../../config.php');

// get the light id from the url
$lightId = $_GET['url'];
$userId = $_SESSION['user_id'];

// get the user's own lights
$lights = $db->prepare("SELECT * FROM tblLights WHERE UserID = :userId");
$lights->bindParam(':userId', $userId);
$lights->execute();
$lightResults = $lights->fetchAll(PDO::FETCH_ASSOC);
    
// get the user's subscribed lights
$feedLights = $db->prepare("SELECT * FROM tblFeed WHERE UserID = :userId");
$feedLights->bindParam(':userId', $userId);
$feedLights->execute();
$feedLightResults = $feedLights->fetchAll(PDO::FETCH_ASSOC);

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
  
  <title>Greenlight - User Settings</title>
  
  <!-- css -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/style.css" rel="stylesheet">
  
  <!-- js / jquery -->
  <script src="/js/jquery-2.2.4.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/dash-nav-js.js"></script>
  
</head>

<body>
  <!-- nav bar + header -->
  
  <?php
  
  if(!isset($_SESSION['user_name'])){ 
    include("../../modules/nav.php");
  }
  else{
    include("../../modules/nav-loggedin.php");
  }
  
  ?>
  
  <!-- content and footer -->
  <div class="container">
  
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">
            Edit Light 
          </h1>
      </div>
     
     <?php include("../../modules/dash-nav.php"); ?>
     
     <div class='col-md-6'>
         <div class='panel-heading'></div>
         Edit light #<?php echo $lightId ?>
         
        </div>
        
     
    </div>
    
    <hr>
    
    <!-- footer -->
    <?php include("../../modules/footer.php") ?>
    
  </div>
  <!-- /contatiner -->
</body>

</html>
