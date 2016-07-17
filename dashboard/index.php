<!DOCTYPE html>

<?php
session_start();
if(!isset($_SESSION['user_name'])){ //if login in session is not set
  header("Location: ../login");
}

include('../config.php');

$userId = $_SESSION['user_id'];

$lights = $db->prepare("SELECT * FROM tblLights WHERE UserID = :userId");
    $lights->bindParam(':userId', $userId);
    $lights->execute();
    $lightResults = $lights->fetchAll(PDO::FETCH_ASSOC);
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
  
  <title>Greenlight - Dashboard</title>
  
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
            <?php echo $_SESSION['user_name']."'s dashboard"; ?>
          </h1>
      </div>
     
     <?php include("../modules/dash-nav.php"); ?>
     
     <div class='col-md-6'>
       <div class='panel-heading'>Lights</div>
     
     <?php
     
     foreach($lightResults as $lights){
       
       // get corresponding hex value for the colour id of the light.
       $lightColour = $db->prepare("SELECT * FROM tblLightColour WHERE ColourID = :colourId");
       $lightColour->bindParam(':colourId', $lights['ColourID']);
       $lightColour->execute();
       $hexCode = $lightColour->fetch(PDO::FETCH_ASSOC);
       
       $lightUrl = "https://greenlight-drop-table-team-hypnotik.c9users.io/lights/".$lights['LightID'];
       
       $bgColour = $hexCode['HexValue'];
       
       if($lights['State']==0){
         $bgColour = "#7E7E7E";
       }
       
       // the background colour of the lights change inline here. (dont really know how it's going to work when we have a graphic for the lights.)
       echo "
              
        <div class='panel panel-default' style='padding-left: 10px; padding-right: 10px'; padding-bottom:10px'>
        
          <div class='row'>
            <div class='panel-body'>
              <div class='col-md-2 temp-greenlight' style='background: ".$bgColour."';'>
              </div>
              <div class='col-md-10'>
                <b><a href='/lights/".$lights['LightID']."'>".$lights['LightTitle']."</a></b>
                <p>
                  <small>".nl2br($lights['Description'])."</small>
                </p>
              </div>
            </div>
          </div>
            
             <div class='row'>
               <div class='col-md-12'>
                 <span class='pull-right'>Share | Edit</span>
               </div>
             </div>
            
             <div class='row' style='margin-bottom:10px; '>
               <div class='col-md-12'>
                 <div class='input-group'>
                   <input type='text' class='form-control' value='".$lightUrl."' readonly>
                   <span class='input-group-btn'>
                   <button class='btn btn-secondary' type='button'>Copy</button>
                   </span>
                 </div>
               </div>
             </div>
          </div>
          
          ";
            
            }
            
            ?>
            
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
    <?php include("../modules/footer.php") ?>
    
  </div>
  <!-- /contatiner -->
</body>

</html>
