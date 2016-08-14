<!DOCTYPE html>

<?php
session_start();
include('../../config.php');

if(isset($_POST['submit'])){
  $errMsg = '';
  
  // Get all the form data and assign them to variables for easy use
  $lightName = trim($_POST['lightname']);
  $lightDesc = trim($_POST['lightdesc']);
  $lightColour = trim($_POST['lightclr']);
  $lightState = trim($_POST['lightstate']);
  $lightPublic = trim($_POST['lightpublic']);
  $userID = $_SESSION['user_id'];
  
  // lightPublic and lightState are 'on' if checked and have no value if unchecked, check if they have values then set them accordingly. 
  if($lightState==''){
    // initialise light to off
    $lightState = 0;
  }
  else{
    // initialise light to on
    $lightState = 1;
  }
  
  if($lightPublic==''){
    // initialise light to private
    $lightPublic = 0;
  }
  else{
    //initialise light to public
    $lightPublic = 1;
  }

  // Get the ColourID I need from the database 
  $tblLightColourRecords = $db->prepare("SELECT ColourID FROM tblLightColour WHERE HexValue = :lightColour");
  $tblLightColourRecords->bindParam(':lightColour', $lightColour);
  $tblLightColourRecords->execute();
  $tblLightColourResults = $tblLightColourRecords->fetch(PDO::FETCH_ASSOC);

  $lightColour = $tblLightColourResults['ColourID'];
  
  try{
    $newLightQuery = $db->prepare("INSERT INTO tblLights(UserID, TriggerTypeName, TriggerValuesID, ColourID, LightType, Public, State, GroupLight, InviteAllowed, PostToSocialMedia, LightSocialMediaID, Reoccurrence, LightDeleted, Description, LightTitle) VALUES (:userID, 5, NULL, :colourID, 0, :public, :state, 0, 1, 0, NULL, 0, 0, :description, :title)");
    $newLightQuery->bindParam(':userID', $userID);
    $newLightQuery->bindParam(':colourID', $lightColour);
    $newLightQuery->bindParam(':public', $lightPublic, PDO::PARAM_INT);
    $newLightQuery->bindParam(':state', $lightState, PDO::PARAM_INT);
    $newLightQuery->bindParam(':description', $lightDesc);
    $newLightQuery->bindParam(':title', $lightName);
    $newLightQuery->execute();
    
    //Get the new ID for the light as it is an auto increment field
    $newLightId = $db->lastInsertId();
    $insertSuccess=true;
    
  }
  catch (PDOException $e){
    if ($e->getCode() == 1062) {
      // dont think you can get this exception with this form but better to be safe
      $errMsg .= 'Key constraint violation.<br>'; //TODO make this a more user friendly error. 
    }
    else{
      throw $e; //TODO
    }
    
  }
  
  if(insertSuccess){
    // redirect the user to the newly created lights' page
    header("Location: ../../lights/".$newLightId);
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
  
  <title>Greenlight - Dashboard - Create Basic Light</title>
  
  <!-- css -->
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/style.css" rel="stylesheet">
  <link href="../../css/bootstrap-colorselector.css" rel="stylesheet">
  
  <!-- js / jquery -->
  <script src="../../js/jquery-2.2.4.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/dash-nav-js.js"></script>
  <script src="../../js/bootstrap-colorselector.js"></script>
  <script src="../../js/bootstrap-toggle.min.js"></script>
  
  <!-- opt-in for bootstrap tooltips because they are auto-off for performance reasons -->
  <script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    
  })
  </script>
  
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
  
  <?php
      if(isset($errMsg)){
        echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$errMsg.'</div>';
      }
    ?>
    
    <div class="row">
        
      <div class="col-lg-12">
        <h1 class="page-header">
          <?php echo "Create Basic Light"; ?>
        </h1>
      </div>
      
      <?php include("../../modules/dash-nav.php"); ?>
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-body">
            
             <form action="" method="post" class="form-horizontal">
                <div class="form-group">
                  <label for="lightName" class="col-sm-3 control-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="lightName" placeholder="Name" name="lightname" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="lightDesc" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" rows="4" id="lightDesc" placeholder="Description" name="lightdesc" max-length="5" required></textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="colorselector" class="col-sm-3 control-label">Colour</label>
                  <div class="col-sm-9">
                
                    <select id="colorselector" name="lightclr">
                      <option value="#5CB85C" data-color="#5CB85C" selected="selected"></option>
                      <option value="#A0522D" data-color="#A0522D"></option>
                      <option value="#FF4500" data-color="#FF4500"></option>
                      <option value="#DC143C" data-color="#DC143C"></option>
                      <option value="#FF8C00" data-color="#FF8C00"></option>
                      <option value="#C71585" data-color="#C71585"></option>
                      <!-- add more colours -->
                      </select>
                      
                    <script>
                      $('#colorselector').colorselector();
                    </script>
                
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="state" class="col-sm-3 control-label">Initial State <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Choose whether your light will initialise as on or off."></span></label>
                  <div class="col-sm-9">
                    <div class="checkbox">
                  <label>
                    <input id="state" type="checkbox" data-toggle="toggle" data-onstyle="success" name="lightstate">
                  </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <div class="checkbox">
                  <label>
                    <input type="checkbox" name="lightpublic"> Public
                  </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <input class="btn btn-success" type="submit" name='submit' value="Create" />
                    <input class="btn btn-default" type="button" name='cancel' value="Cancel" onclick="window.location='/dashboard.php';" />
                  </div>
                </div>
          
              </form>
              
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="panel panel-default">
          
          <div class="panel-body">
            <div class="temp-greenlight"></div>
            <div id="nameTarget" class="light-name"></div>
            <div id="descTarget" class="light-desc"></div>
            
            <script type="text/javascript">
            
            // jquery live updaters for the light preview
            
            // live updater for the light name
            $('#lightName').keyup(function() {
              var keyed = $(this).val().replace(/\n/g, '<br/>');
              $("#nameTarget").html(keyed);
            });
            
            // live updater for the light description
            // TODO needs to wrap to container after 41 consecutive characters or they will go outside the box
            $('#lightDesc').keyup(function() {
              var keyed = $(this).val().replace(/\n/g, '<br/>');
              $("#descTarget").html(keyed);
            });
            
            // live updater for the light colour
            $('#colorselector').change(function() {
              var slctdClr = $(this).val();
              $(".temp-greenlight").css("background-color", slctdClr);
            });
            
            // limit number of characters in the text area while typing and also on paste
            $('textarea').on('paste keyup keydown', function(event) {
              var element = this;
              setTimeout(function() {
                if ($(element).val().length > 254) {
                  if (event.type == "paste") {
                    $(element).val($(element).val().substr(0, 254))
                  }
                  else {
                    $(element).val($(element).val().slice(0, -1));
                  }
                  alert("light description may only contain up to 255 characters.");
                }
                else {
                  return true;
                }
              }, 100);
            });
            </script>
            
          </div>
        </div>
      </div>
      
    </div>
    
    <hr>
    
    <!-- footer -->
    <?php include("../../modules/footer.php") ?>
    
  </div>
  <!-- /contatiner -->
</body>

</html>
