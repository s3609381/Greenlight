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