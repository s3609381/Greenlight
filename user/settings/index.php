<!DOCTYPE html>

<?php
session_start();

if(!isset($_SESSION['user_name'])){ //if login in session is not set
  header("Location: ../../login");
}
if(!isset($_GET['url'])){
    // redirect back to login or dashboard if user tries to access user/settings/index.php directly
    header("Location: ../../login");
}


include('../../config.php');

// get the user ID of the profile
$profileUserId = $_GET['url'];

// get the user from the id in the url out of db
$getUser = $db->prepare("SELECT * FROM tblUsers WHERE UserID = :userId");
$getUser->bindParam(':userId', $profileUserId);
$getUser->execute();
$user = $getUser->fetch(PDO::FETCH_ASSOC);

// get user details from the user details table
$getUserDetails = $db->prepare("SELECT * FROM tblUserDetailsSettings WHERE UserID = :userId");
$getUserDetails->bindParam(':userId', $profileUserId);
$getUserDetails->execute();
$userDetails = $getUserDetails->fetch(PDO::FETCH_ASSOC);

// set all the current values

// current email
$currentEmail = $user['Email'];

// current mobile
if($userDetails['MobileNumber']!=NULL){
  $currentMobile = $userDetails['MobileNumber'];
}else{
  $currentMobile = "No mobile number has been specified for this account.";
}

// current default light colour
$currentDefaultColourID = $userDetails['DefaultLightColourID'];

// current password
$currentPassword = $user['Password'];

// get Hex Value from colour ID
$tblLightColourRecords = $db->prepare("SELECT * FROM tblLightColour WHERE ColourID = :colourId");
$tblLightColourRecords->bindParam(':colourId', $currentDefaultColourID);
$tblLightColourRecords->execute();
$tblLightColourResults = $tblLightColourRecords->fetch(PDO::FETCH_ASSOC);

$lightHexValue = $tblLightColourResults['HexValue'];

if(isset($_POST['update'])){
  $successMsg = '';
 
  // get values from form
  $newEmail = trim($_POST['newEmail']);
  $newMob = trim($_POST['mobile']);
  $newPassword = trim($_POST['password']);
  $newDefaultLightHexValue = trim($_POST['lightclr']);
 
  //echo $newEmail."<br/>".$newMob."<br/>".$newPassword."<br/>".$newDefaultLightHexValue;
  
  // update tbl users (will begin with an if statement to see if email or password haven't been changed, no point running the query if they're both empty)
  if($newEmail!='' || $newPassword!=''){
    // if email hasn't been updated - set it to the old one
    if($newEmail==''){
      $newEmail = $currentEmail;
    }
    // if password hasn't been updated - set it to the old one
    if($newPassword==''){
    $newPassword = $currentPassword;
    }
    
    try{
    $updateUser = $db->prepare("UPDATE tblUsers SET Email = :newEmail, Password = :newPassword WHERE UserID = :userID");
    $updateUser->bindParam(':newEmail', $newEmail);
    $updateUser->bindParam(':newPassword', $newPassword);
    $updateUser->bindParam(':userID', $profileUserId);
    $updateUser->execute();
    
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
  }

  // update tbl user details (again, check to make sure at least one of the values has actually been updated before running anything)
  if($newMob!='' || $newDefaultLightHexValue != $lightHexValue){
    // if mobile number hasn't been updated - set it to the old one
    if($newMob==''){
      $newMob = $currentMobile;
    }
    // (if the colour hasn't changed don't need to set it to the old value because it's the same but will need to get the colour ID)
    // Get the ColourID I need from the database 
    $tblLightColourRecords = $db->prepare("SELECT ColourID FROM tblLightColour WHERE HexValue = :lightColour");
    $tblLightColourRecords->bindParam(':lightColour', $newDefaultLightHexValue);
    $tblLightColourRecords->execute();
    $tblLightColourResults = $tblLightColourRecords->fetch(PDO::FETCH_ASSOC);

    $newDefaultLightID = $tblLightColourResults['ColourID'];
    
    try{
    $updateUserDetails = $db->prepare("UPDATE tblUserDetailsSettings SET DefaultLightColourID = :lightID, MobileNumber = :newMobile WHERE UserID = :userID");
    $updateUserDetails->bindParam(':lightID', $newDefaultLightID);
    $updateUserDetails->bindParam(':newMobile', $newMob);
    $updateUserDetails->bindParam(':userID', $profileUserId);
    $updateUserDetails->execute();
    
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
    
  }
  
  // Display message to user that their update was successful or remind them to enter stuff in the form if they left all fields empty. 
  if($updateSuccess){
    $successMsg .= 'Details updated successfully.';
  }else{
    $successMsg .= 'Please enter details you wish to update.';
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
  
  <title>Greenlight - Account Settings</title>
  
  <!-- css -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/style.css" rel="stylesheet">
  <link href="/css/bootstrap-colorselector.css" rel="stylesheet">
  <link href="/css/bootstrapValidator.min.css" rel="stylesheet">

  <!-- js / jquery -->
  <script src="/js/jquery-2.2.4.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/dash-nav-js.js"></script>
  <script src="/js/bootstrap-colorselector.js"></script>
  <script src="/js/bootstrapValidator.min.js"></script>
  
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
      if(isset($successMsg)){
        echo '<div style="color:#5cb85c;text-align:center;font-size:12px;">'.$successMsg.'</div>';
      }
    ?>
  
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">
            Account Settings 
          </h1>
      </div>
     
     <?php include("../../modules/dash-nav.php"); ?>
     
     <div class='col-md-6'>
       <div class='panel-heading'></div>
       
       <form action="" method="post" class="form-horizontal" id="updateForm">
                
                <div class="form-group">
                  <label for="inputEmail" class="col-sm-4 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="inputEmail" placeholder="New Email" name="newEmail">
                    <p class="form-text"><?php echo $currentEmail; ?></p>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputMob" class="col-sm-4 control-label">Mobile No.</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputMob" placeholder="Mobile Number" name="mobile">
                    <p class="form-text"><?php echo $currentMobile; ?></p>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputPassword" class="col-sm-4 control-label">New Password</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="inputPassword" placeholder="New Password" name="password">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputConfPassword" class="col-sm-4 control-label">Confirm Password</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="inputConfPassword" placeholder="Confirm Password" name="confirmPassword">
                  </div>
                </div>
                
                <!-- bootstrap validator jquery -->
                <script>
                
                $(document).ready(function() {
                  $('#updateForm').bootstrapValidator({
                    feedbackIcons: {
                      valid: 'glyphicon glyphicon-ok',
                      invalid: 'glyphicon glyphicon-remove',
                      validating: 'glyphicon glyphicon-refresh'
                      },
                      fields: {
                        mobile: {
                          validators: {
                            regexp: {
                              // user may only enter numbers, did not specify character limits because different countries have different mobile number lengths
                              regexp: /^[0-9]+$/,
                              message: 'Mobile number may only contain numbers.'
                            },
                          }
                        },
                        password: {
                          validators: {
                            regexp: {
                              // regex to check for legal password characters
                              regexp: /^[a-zA-Z0-9äöüÄÖÜ!#]+$/,
                              message: 'Password may only contain letters, numbers, ! and #.'
                            },
                            stringLength: {
                              // password must be at least 8 characters
                              min: 8,
                              message: 'Password must be at least 8 characters long.'
                            }
                          }
                        },
                        confirmPassword: {
                          validators: {
                            identical: {
                              // check to make sure that the confirm password matches the new password
                              field: 'password',
                              message: 'Password does not match.'
                              }
                            }
                          },
                          // check to make sure email is valid - html5 does this but want to make the form validation consistent. 
                          newEmail: {
                            message: 'Not a valid email.'
                          }
                        }
                      });                                              
                    });
                    
                </script>
                
                <div class="form-group">
                  <label for="defaultColour" class="col-sm-4 control-label">Default Light Colour</label>
                  <div class="col-sm-8">
                    
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
                      }                    }
                    echo "</select>";
                    ?>
                    
                    <script>
                      $('#colorselector').colorselector();
                    </script>
                    
                  </div>
                </div>
                
                 <div class="form-group">
                  <label for="selectNotif" class="col-sm-4 control-label">Notifications</label>
                  <div class="col-sm-8">
                    <select class="custom-select" id="selectNotif">
                      <option value="1">Email</option>
                      <option value="2">SMS</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-offset-4 col-sm-10">
                    <input class="btn btn-success" type="submit" name='update' value="Update" />
                    <input class="btn btn-default" type="button" name='cancel' value="Cancel" onclick="window.location='/dashboard/';" />
                  </div>
                </div>
              </form>
            
        </div>
     
    </div>
    
    <hr>
    
    <!-- footer -->
    <?php include("../../modules/footer.php") ?>
    
  </div>
  <!-- /contatiner -->
</body>

</html>
