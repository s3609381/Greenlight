<!DOCTYPE html>

<?php

session_start();

if(!isset($_GET['url'])){
    // redirect back to login or dashboard if user tries to access lights.php directly
    header("Location: ../login.php");
}
else{
    include('../config.php');
    
    // get the light id from the url
    $lightId = $_GET['url'];
    
    $records = $db->prepare("SELECT * FROM tblLights WHERE LightID = :lightId");
    $records->bindParam(':lightId', $lightId);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    
    // check if this lightId exists in the database
    if($results==0){
        // TODO Redirect to page stating that the light does not exist. 
        echo "Light ID does not exist in database.";
    }
    // check if this light has been deleted.
    elseif($results['lightDeleted']==1){
        // TODO Redirect to page stating that the light does not exist. 
        echo "Light ID does not exist in database.";
    }
    else{
        $lightID = $results['LightID'];
        $lightOwner = $results['UserID'];
        $lightViewer = $_SESSION['user_id'];
        $lightPublic = $results['Public'];
        
        // find out if the light is public or private (public = 0 is private, public = 1 is public)
        if($lightPublic == 0){
            
            // if the light is private then need to check if the user is the owner of the light and allowed to view it
            if($lightOwner==$lightViewer){
            echo "Authorised to view light ".$lightID;
            }
            else{
                
            // TODO redirect to page stating that the light is unavailable for public view. 
            echo "Sorry, this light isn't available for public view.";
            }
        }
        
        else{
            echo "This is public light ".$lightID;
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

  <title>Greenlight</title>

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

      <!-- misc test content -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Light # <?php echo $lightID ?>
                </h1>
            </div>
            <div class="col-md-12">
                <div class="panel panel-no-border">
                    <div class="panel-body">
                        <p><h2><a href = "/dashboard">Back to Dashboard</a></h2></p>
                    </div>
                </div>
            </div>
          </div>
        <hr>

        <!-- footer -->
        <?php include("../modules/footer.php") ?>

    </div><!-- /contatiner -->
</body>

</html>