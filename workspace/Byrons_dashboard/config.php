<?php
//DB config
define('_HOST_NAME_', 'localhost'); 
define('_USERNAME_', 'greenlight');
define('_DB_PASSWORD', ''); 
define('_DB_NAME_', 'greenlight');
	
//PDO Database Connection
try {
 $db = new PDO('mysql:host='._HOST_NAME_.';dbname='._DB_NAME_, _USERNAME_, _DB_PASSWORD);
 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 echo "Database Connected successfully"; 
} catch(PDOException $e) { 
echo "Connection failed: " . $e->getMessage(); 
} 
?>