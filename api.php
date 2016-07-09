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

function parseLoginRequest() {
	echo "reached the parselogin function";
	$method = $_SERVER['REQUEST_METHOD'];
	switch ($method) {
		case 'POST':
			login();
			break;
		default:
			http_response_code(401);
			break;
	}	

}

function login()
{
	include('config.php');

	echo "reached the login function \n";
	$authenticated = false;
	
	//php://input = the body of a request sent to the site
	
	$input = json_decode(file_get_contents('php://input'),true);
	
	$requestUser = $input['username'];
	$requestPassword = $input['password'];
	
	//check if the credentials match the records in the DB
	$records = $db->prepare("SELECT * FROM tblUsers WHERE UserName = :username AND Password = :password ");
		$records->bindParam(':username', $requestUser);
		$records->bindParam(':password', $requestPassword);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		if($results > 0){
			
			//If the credentials match return a session token
			$authenticated = true; 
			$token = createSessionToken(32);
			//need to get the userid but i dont want to waste memory be querying the database again.
			//finally figured it out
			$userId = $results['UserId'];
			//insertSessionToken($userId, $token);
			echo "Session : $token";
			
		}else{
			// if they dont return the HTTP response code 401
			echo "Bad Credentials";
			http_response_code(401);
		}

}
//this bit needs some love
function insertSessionToken($requestUser, $token)
{
	include('config.php');
	$query = $SQL->prepare('INSERT INTO tblSessions :');
	$query->bindValue(':uid', $user_id, PDO::PARAM_INT);
	$query->execute();
	
	
	
}

function getLights() 
	// Should do a check here to see if a sessionId has been provided in the request.

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


function createSessionToken($val){
	//http://stackoverflow.com/questions/17596605/how-to-generate-unique-session-id-without-the-use-of-session-id
      $chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-";
      srand((double)microtime()*1000000);
      $i = 0;
      $pass = '' ;
      while ($i<=$val) 
    {
        $num  = rand() % 33;
        $tmp  = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
      }
    return $pass;
    }
    
    
 ?>