<!DOCTYPE html>
<?php
session_start();
?>
<p>This page is a session dump, it allows you to check whats in session.</p>
<?php
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';

?>
<html>
   
   <head>
      <title>Home | Dashboard</title>
   </head>
   
   <body>
<h2><a href = "dashboard.php">Back to Dashboard</a></h2>
 </body>
   
</html>