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

if(isset($_POST['delete'])){
  $deleteLight = $db->prepare("UPDATE tblLights SET LightDeleted = 1 WHERE LightID = :lightId");
  $deleteLight->bindParam(':lightId', $lightId);
  $deleteLight->execute();
  
  header("Location: ../../dashboard/");
  
}

if(isset($_POST['submit'])){
  
  // get the ne  values from the form
  $newLightName = trim($_POST['lightname']);
  $newLightDesc = trim($_POST['lightdesc']);
  $newLightColour = trim($_POST['lightclr']);
  $newLightState = trim($_POST['lightstate']);
  $newLightPublic = trim($_POST['lightpublic']);
  
  if($newLightState=="on"){
    $newLightState = 1;
  }
  else{
    $newLightState = 0;
  }
  
  if($newLightPublic=="on"){
    $newLightPublic = 1;
  }
  else{
    $newLightPublic = 0;
  }
  
  // Get the ColourID I need from the database 
  $tblLightColourRecords = $db->prepare("SELECT ColourID FROM tblLightColour WHERE HexValue = :lightColour");
  $tblLightColourRecords->bindParam(':lightColour', $newLightColour);
  $tblLightColourRecords->execute();
  $tblLightColourResults = $tblLightColourRecords->fetch(PDO::FETCH_ASSOC);

  $newLightColourId = $tblLightColourResults['ColourID'];
  
  try{
    $updateLight = $db->prepare("UPDATE tblLights SET ColourID = :colourId, Public = :public, State = :state, Description = :desc, LightTitle = :title WHERE LightID = :lightId");
    $updateLight->bindParam(':lightId', $lightId);
    $updateLight->bindParam(':colourId', $newLightColourId);
    $updateLight->bindParam(':public', $newLightPublic, PDO::PARAM_INT);
    $updateLight->bindParam(':state', $newLightState,  PDO::PARAM_INT);
    $updateLight->bindParam(':desc',  $newLightDesc);
    $updateLight->bindParam(':title',  $newLightName);
    $updateLight->execute();
    
    $updateSuccess = true;
    
  }
  catch (PDOException $e){
    if ($e->getCode() == 1062) {
      // dont think you can get this exception with this form but better to be safe
      $errMsg .= 'Key constrain violation.<br>'; //TODO make this a more user friendly error. 
    }
    else{
      throw $e; //TODO
    }
  }
  
  if($updateSuccess){
    // redirect the user to the newly update lights' page
    header("Location: ../../lights/".$lightId);
    
  }
}

// get the user's own lights
$lights = $db->prepare("SELECT * FROM tblLights WHERE LightID = :lightId");
$lights->bindParam(':lightId', $lightId);
$lights->execute();
$lightResults = $lights->fetch(PDO::FETCH_ASSOC);

// assign all the fetched light details to variables to display to the user
$lightDesc = $lightResults['Description'];
$lightTitle = $lightResults['LightTitle'];
$lightState = $lightResults['State'];
$lightPublic= $lightResults['Public'];
$lightColourID = $lightResults['ColourID'];

// get Hex Value from colour ID
$tblLightColourRecords = $db->prepare("SELECT * FROM tblLightColour WHERE ColourID = :colourId");
$tblLightColourRecords->bindParam(':colourId', $lightColourID);
$tblLightColourRecords->execute();
$tblLightColourResults = $tblLightColourRecords->fetch(PDO::FETCH_ASSOC);

$lightHexValue = $tblLightColourResults['HexValue'];

    
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
  
  <title>Greenlight - Edit Light</title>
  
  <!-- css -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/style.css" rel="stylesheet">
  <link href="/css/bootstrap-colorselector.css" rel="stylesheet">
  
  <!-- js / jquery -->
  <script src="/js/jquery-2.2.4.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/dash-nav-js.js"></script>
  <script src="/js/bootstrap-colorselector.js"></script>
  <script src="/js/bootstrap-toggle.min.js"></script>
  
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
  
    <div class="row">
        
      <div class="col-lg-12">
        <h1 class="page-header">
          Edit Light
        </h1>
      </div>
      
      <?php include("../../modules/dash-nav.php"); ?>
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-body">
            
            <!-- the same form as is on the create light page - but with all the inputs filled / switched according to the information in the light table in the db -->
            
             <form action="" method="post" class="form-horizontal">
                <div class="form-group">
                  <label for="lightName" class="col-sm-3 control-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="lightName" placeholder="Name" name="lightname"  value ="<?php echo $lightTitle ?>" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="lightDesc" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" rows="4" id="lightDesc" placeholder="Description" name="lightdesc" max-length="5" required><?php echo $lightDesc ?></textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="colorselector" class="col-sm-3 control-label">Colour</label>
                  <div class="col-sm-9">
                    
                    <?php 
                    
                    // create an array of the colours in the colour selector then iterate through while creating the drop down menu to make sure the correct colour is selected.
                    $colourArray = array("#5CB85C", "#A0522D", "#FF4500", "#DC143C", "#FF8C00", "#C71585", "#EF476F", "#FFD166", "#06C995", "#2F8CAA", "#845F82", "#AAC5AE", "#72584D", "#FAF4C1", "#FF6666", "#16BFEE", "#10FFCA", "#87BB3F", "#F4E226", "#B22AA4");
                    
                    echo "<select id='colorselector' name='lightclr'>";
                    
                    foreach($colourArray as $colour){
                      
                      if($colour == $lightHexValue){
                        
                        echo "<option value='"."$colour"."' data-color='"."$colour"."' selected='selected'></option>";
                        
                      }
                      else{
                        
                        echo "<option value='"."$colour"."' data-color='"."$colour"."'></option>";
                        
                      }
                    }
                    
                    echo "</select>";
                    
                    ?>
                      
                    <script>
                      $('#colorselector').colorselector();
                    </script>
                
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="state" class="col-sm-3 control-label">State <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Turn light on or off."></span></label>
                  <div class="col-sm-9">
                    <div class="checkbox">
                  <label>
                    
                    <?php 
                    
                    if($lightState){
                      
                      echo "<input id='state' type='checkbox' data-toggle='toggle' data-onstyle='success' name='lightstate' checked>";
                      
                    }
                    
                    else{
                      
                      echo "<input id='state' type='checkbox' data-toggle='toggle' data-onstyle='success' name='lightstate'>";
                    }
                    
                    ?>
                    
                  </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <div class="checkbox">
                  <label>
                    
                    <?php 
                    
                    if($lightPublic){
                      
                      echo "<input type='checkbox' name='lightpublic' checked> Public";
                      
                    }
                    
                    else{
                      
                      echo "<input type='checkbox' name='lightpublic'> Public";
                    }
                    
                    ?>
                    
                  </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <input class="btn btn-success" type="submit" name='submit' value="Update" />
                    <input class="btn btn-default" type="button" name='cancel' value="Cancel" onclick="window.location='/dashboard/';" />
                  </div>
                </div>
                
                
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <hr/>
                    <input class="btn btn-danger btn-sm pull-right" type="submit" name='delete' value="Delete" onclick="return confirm('Are you sure you wish to delete this light? This action cannot be undone.')" />
                  </div>
                </div>
          
              </form>
              
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="panel panel-default">
          
          <div class="panel-body">
            <div class="temp-greenlight" style="background-color: <?php echo $lightHexValue; ?>"></div>
            <div id="nameTarget" class="light-name"><?php echo $lightTitle; ?></div>
            <div id="descTarget" class="light-desc"><?php echo $lightDesc; ?></div>
            
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
