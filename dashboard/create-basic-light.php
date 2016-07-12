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
  <link href="../css/bootstrap-colorselector.css" rel="stylesheet">
  
  <!-- js / jquery -->
  <script src="../js/jquery-2.2.4.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/dash-nav-js.js"></script>
  <script src="../js/bootstrap-colorselector.js"></script>
  <script src="../js/bootstrap-toggle.min.js"></script>
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
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-body">
            
             <form action="" method="post" class="form-horizontal">
                <div class="form-group">
                  <label for="lightName" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="lightName" placeholder="Name" name="lightname" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="lightDesc" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="4" id="lightDesc" placeholder="Description" name="lightdesc" required></textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="colorselector" class="col-sm-2 control-label">Colour</label>
                  <div class="col-sm-10">
                
                    <select id="colorselector">
                      <option value="#5CB85C" data-color="#5CB85C" selected="selected"></option>
                      <option value="#A0522D" data-color="#A0522D"></option>
                      <option value="#FF4500" data-color="#FF4500"></option>
                      <option value="#DC143C" data-color="#DC143C"></option>
                      <option value="#FF8C00" data-color="#FF8C00"></option>
                      <option value="#C71585" data-color="#C71585"></option>
                      </select>
                      
                    <script>
                      $('#colorselector').colorselector();
                    </script>
                
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="state" class="col-sm-2 control-label">State</label>
                  <div class="col-sm-10">
                    <div class="checkbox">
                  <label>
                    <input id="state" type="checkbox" checked data-toggle="toggle" data-onstyle="success">
                  </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                  <label>
                    <input type="checkbox"> Public
                  </label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-success" type="submit" name='submit' value="Create" />
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
            
            </script>
            
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
