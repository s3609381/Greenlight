<?php
/** This is the Greenlight API Wrapper.
 * It contains functions in information that allow users to securely acces the sites functions through a different presentaion (the API).
*/


if (!isset($_GET['url'])) {
	// We have not been redirected here from the 
	// .htaccess file, so it's not an API call
    header("Location: login.php");
}
else {

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
	//Here we figure out what type of request the user sent and if its supported call the relevant function or return HTTP status code of 401
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
	//Hands a POST request for /login
	
	//include the sql library
	include('config.php');

	
	//php://input = the body of a request sent to the site
	$input = json_decode(file_get_contents('php://input'),true);
	
	//get the username and password that the user provided in the request
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
			$userId = $results['UserID'];
			echo $userId;
			insertSessionToken($userId, $token);
			echo "Session : $token";
			
		}else{
			// if they dont return the HTTP response code 401
			echo "Bad Credentials";
			http_response_code(401);
		}
}

function insertSessionToken($userId, $token)
{
	//This function will insert a session token into the database
	
	//include the sql library
	include('config.php');
	
	
	//We need an australian timestamp for the session token
	//Get the time as of now
	$currentTime = new DateTime("now", new DateTimeZone('Australia/Melbourne') );
	//Add 30 minutes to it
	$currentTime->modify("+30 minutes");
	//Turn it into a string
	$sessionExpirationDateString = $currentTime->format('Y-m-d H:i:s');

	//Now we try to insert the session token into the database.
	try {
    	$query = $db->prepare("INSERT INTO tblSessions (SessionId, UserId, SocialMediaID, AuthenticationTime, SessionExpiration) VALUES (:token, :userId, NULL, NOW(), :sessionExpirationDate)");
		$query->bindParam(':token', $token);
		$query->bindParam(':userId', $userId);
		$query->bindParam(':sessionExpirationDate', $sessionExpirationDateString);
		$query->execute();
		} 
	catch (PDOException $e) {
        	throw $e;
    	}
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
 
 function checkSession($requestSession){
 	include ('config.php');
 	
 		$records = $db->prepare("SELECT * FROM tblSessions WHERE SessionID = :session");
		$records->bindParam(':session', $requestSession);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		if($results > 0){
			$now = time();
			$sessionExpiry = $results['SessionExpiration'];
			
			if($now < $sessionExpiry) {
				return true;	
			}
			else {
				echo 'Sorry the session you provided has expired. Please send a POST request to /login to get a new session.';
				return false;
			}
		}
		
		else {
			echo 'Sorry the session you provided does not exist. Please send a POST request to /login to get a new session.';
			return false;
		}
 	
 }
    
 ?>