<?php
// API in PHP example
/*

1. Ascertain if the user is allowed to access the API
	1.1. If not, return 401 (Unauthorised)
2. Direct request based on routing rules
3. Perform request
4. Return result

To get here, load this into apache, make sure mod_rewrite is enabled, then navigate to <host>/api/users or <host>/api/configuration
*/

echo "<h2>Reached the API Redirect!</h2>";
echo "Hello world!<br>";
echo "Theres a heap of errors here. Don't worrry its a work in progress :)";


if (!isset($_GET['url'])) {
	// We have not been redirected here from the 
	// .htaccess file, so it's not an API call
    header("Location: login.php");
}
else {
	// Include the SQL library provided
	include('config.php');
	
	//Get the method (GET,POST,PATCH,PUT,DELETE)
	$method = $_SERVER['REQUEST_METHOD'];
	
	echo "The request sent to this site was a <h1>$method</h1> request \n";
	


	// Get the redirected path from the $_GET collection 
	// The request path will be everything past /api for example /api/login $requestpath = login
	$requestPath = $_GET['url'];

	switch ($requestPath) {
		case 'login':
			parseLoginRequest();
			break;
		case 'lights':
			getLights();
			break;
		default:
			processError(404);
			break;
	}
}
// it works down to here



function parseLoginRequest() {
	echo "reached the parselogin function";
	$method = $_SERVER['REQUEST_METHOD'];
	echo "\n $method method";
	switch ($method) {
		case 'POST':
			login();
			break;
		default:
			//Return unauthorised if its not a GET or a POST
			//processError(401);
			break;
	}	

}

function login()
{
	echo "reached the login page \n";
	$hardcodedUser = '1';
	$hardcodedPassword = '2';
	
	
	//php://input = the body of a request sent to the site
	$input = json_decode(file_get_contents('php://input'),true);
	echo $input['username'];
	echo $input['password'];

	//$result = array();
	//$result['username'] = 'username';
	//processResponseCode(200);
	
	/* 
	If I wanted to get one from a DB, I could do something like:
	$conn = new SQLConnector($server, $database, $user, $password);
	$user = $conn->Select("users", array("*"), "", array("username" => $user));		// Check the utils.sql.php file
	*/
	//echo json_encode($result);
}

function getLights() 
	// Should do a check here to see if a sessionId has been provided.

{
	$result = array();
	$result[] = array('config1' => 'value1');
	$result[] = array('config2' => 'value2');
	//looks like the echo will output the JSON result to the body. So if the user is on a  website it will show on their page.
	
	echo json_encode($result);
}

function processResponse($code, $type = 'application/json') {
	header('Content-Type: ' . $type . ';');
	http_response_code($code);
}

?>