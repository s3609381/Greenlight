<?php
// API in PHP example
/*
This page is used to redirect all 404s to. This means that you can use paths in your browser
that don't point to a specific real location.

For example, you could have site.com/api/users without having to have a folder api > users 
with an index file inside that handles the request. 


1. Ascertain if the user is allowed to access the API
	1.1. If not, return 401 (Unauthorised)
2. Direct request based on routing rules
3. Perform request
4. Return result

To get here, load this into apache, make sure mod_rewrite is enabled, then navigate to <host>/api/users or <host>/api/configuration
*/

if (!isset($_GET['url'])) {
	// We have not been redirected here from the 
	// .htaccess file, so it's not an API call
}
else {
	// Include the SQL library provided
	require_once('utils\utils.sql.php');
	
	// Get the redirected path from the $_GET collection 
	// $_GET is usually the current calls query parameters
	$requestPath = $_GET['url'];

	switch ($requestPath) {
		case 'api/users':
			getUser();
			break;
		case 'api/configuration':
			getConfiguration();
			break;
		default:
			processError(404);
			break;
	}
}

function getUser() {
	$result = array();
	$result['username'] = 'username';
	//processResponseCode(200);
	
	/* 
	If I wanted to get one from a DB, I could do something like:
	$conn = new SQLConnector($server, $database, $user, $password);
	$user = $conn->Select("users", array("*"), "", array("username" => $user));		// Check the utils.sql.php file
	*/
	echo json_encode($result);
}

function getConfiguration() {
	$result = array();
	$result[] = array('config1' => 'value1');
	$result[] = array('config2' => 'value2');
	echo json_encode($result);
}

function processResponse($code, $type = 'application/json') {
	header('Content-Type: ' . $type . ';');
	http_response_code($code);
}

?>