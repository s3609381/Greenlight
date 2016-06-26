<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['login_success'])){ //if login in session is not set
    header("Location: index.php");
}
?>
<html>
   
   <head>
      <title>Home | Dashboard</title>
   </head>
   
   <body>
      <h1><?php echo 'Welcome '.$_SESSION['login_success']; ?> </h1> 
       <h2><a href = "session_dump.php">View Session</a></h2>
      <h2><a href = "logout.php">Sign Out</a></h2>
   </body>
   
</html>