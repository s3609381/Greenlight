<?php

session_start();

if(isset($_SESSION['user_name'])){ //if login in session is not set
    header("Location: /dashboard");
    
}else{
    header("Location: /home");
    
}

?> 