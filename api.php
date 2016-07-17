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

	echo 'Welcome to the greenlight API.';
	echo "please post to /login to get a session ID \n";
	
	// Get the redirected path from the $_GET collection 
	// The request path will be everything past /api for example /api/login $requestpath = login
	$requestPath = $_GET['url'];

	switch ($requestPath) {
		case 'login':
			parseLoginRequest();
			break;
		case 'lights':
			parseLightRequest();
			break;
		default:
			processError(404);
			break;
	}
}


function parseLoginRequest() {
	//Here we figure out what type of request the user sent and if its supported call the relevant function or return HTTP status code of 401 (Unauthorised)
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

function parseLightRequest() {
	//Here we figure out what type of request the user sent and if its supported call the relevant function or return HTTP status code of 405 (Method not allowed)
	$method = $_SERVER['REQUEST_METHOD'];
	switch ($method) {
		case 'POST':
			createLight();
			break;
		case 'GET':
			getLight();
		case 'DELETE':
			deleteLight();
			break;
		case 'PATCH':
			patchLight();
			break;
		default:
			http_response_code(405);
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

function createLight(){
	//include the sql library
	include('config.php');
	
	//php://input = the body of a request sent to the site
	$input = json_decode(file_get_contents('php://input'),true);
	
	$requestName =  $input['name'];
	$requestLightType =  $input['lightType'];
	$requestColourType =  $input['colourId'];
	$requestState =  $input['state'];
	$requestGroupLight =  $input['groupLight'];
	$requestInviteAllowed =  $input['inviteAllowed'];
	$requestPostToSocialMedia =  $input['postToSocialMedia'];
	$requestLightSocialMedia =  $input['lightSocialMedia'];
	$requestSessionId =  $input['sessionId'];
	
	//If sessionId is empty reject the request.
	if($requestSessionId == NULL) {
			echo "Session ID is empty Please POST to the /login resource to recieve a sessionID";
			http_response_code(401);
			return;
	}
	
	$userId = checkSession($requestSessionId);
	// Should do a check here to see if a sessionId has been provided in the request.
	if ($userId > 0){
		if ($requestLightType == 'Basic' || $requestLightType == 'BASIC');
			echo "do PDO stuff \n";
			
			
			
			//build the pdo statement
	/**************************************************

 
  
  try{
    $newLightQuery = $db->prepare("INSERT INTO tblLights(UserID, TriggerTypeName, TriggerValuesID, ColourID, LightType, Public, State, GroupLight, InviteAllowed, PostToSocialMedia, LightSocialMediaID, Reoccurrence, LightDeleted, Description, LightTitle)
    VALUES (:userID, 5, NULL, :colourID, 0, :public, :state, 0, 1, 0, NULL, 0, 0, :description, :title)");
    $newLightQuery->bindParam(':userID', $userID);
    $newLightQuery->bindParam(':colourID', $requestColourType);
    $newLightQuery->bindParam(':public', $lightPublic, PDO::PARAM_INT);
    $newLightQuery->bindParam(':state', $lightState, PDO::PARAM_INT);
    $newLightQuery->bindParam(':description', $lightDesc);
    $newLightQuery->bindParam(':title', $lightName);
    $newLightQuery->execute();
    
    //Get the new ID for the light as it is an auto increment field
    $newLightId = $db->lastInsertId();
    $insertSuccess=true;
    
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
  
  if(insertSuccess){
    // redirect the user to the newly created lights' page
    header("Location: ../../lights/".$newLightId);
  }
  
			
			
			
			/****************************************************/
			
		
	}
	else {
		//exit the function
		return;
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
			$sessionExpiry = $results['SessionExpiration'];
			
			//get the current time
			$currentTime = new DateTime("now", new DateTimeZone('Australia/Melbourne') );
			
			//Turn it into a string
			$formattedCurrentTime = $currentTime->format('Y-m-d H:i:s');
			
			
			//get the userID
			$userId =  $results['UserID'];
			/*DEBUG
			echo "$requestSession \n";
			echo "$formattedCurrentTime \n";
			echo "$sessionExpiry \n";
			*/
						
			if($formattedCurrentTime < $sessionExpiry) {
				return $userId;	
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