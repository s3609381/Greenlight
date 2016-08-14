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
	
	
	echo "Welcome to the greenlight API.\n";
	echo "please post to login to get a session ID";
	
	// Get the redirected path from the $_GET collection 
	// The request path will be everything past /api for example /api/login $requestpath = login
	$requestPath = $_GET['url'];
	$pathId = $_GET['path'];
	

	if ($pathId == 'lights') {
			parseLightRequest();
	}
	elseif ($requestPath == 'login') {
			parseLoginRequest();
	}
	else {
			http_response_code(404);
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
	
	//Get the light resource we need to patch out of the URL
	
	
	switch ($method) {
		case 'POST': 
			createLight();
			break;
		case 'GET':
			getLights();
			break;
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


/*************************************************************************
 * POST /LOGIN 
 * 
/***********************************************************************/

function login()
{
	//Hands a POST request for /login
	echo "login function";
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
	
	//We need an Australian timestamp for the session token
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

/*************************************************************************
 * POST /LIGHTS 
 * 
/***********************************************************************/

function createLight(){
	
	//This function inserts the light referenced in the body of the request into the database.
	
	
	//include the sql library
	include('config.php');
	
	//php://input = the body of a request sent to the site
	$input = json_decode(file_get_contents('php://input'),true);
	
	$requestlightTitle =  $input['lightTitle'];
	$requestDescription = ['description'];
	$requestLightType =  $input['lightType'];
	$requestColourType =  $input['colourId'];
	$requestPublic =  $input['public'];
	$requestState =  $input['state'];
	$requestGroupLight =  $input['groupLight'];
	$requestInviteAllowed =  $input['inviteAllowed'];
	$requestPostToSocialMedia =  $input['postToSocialMedia'];
	$requestLightSocialMedia =  $input['lightSocialMedia'];
	$requestReoccurrence = $input['reoccurrence'];
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
		if ($requestLightType == 'basic' || $requestLightType == 'BASIC');
			echo "do PDO stuff \n";
			echo "$requestDescription \n";
			
			
	//build the pdo statement
  try{
    $newLightQuery = $db->prepare("INSERT INTO tblLights(UserID, TriggerTypeName, TriggerValuesID, ColourID, LightType, Public, State, GroupLight, InviteAllowed, PostToSocialMedia, LightSocialMediaID, Reoccurrence, LightDeleted, Description, LightTitle)
    VALUES (:userID, 5, NULL, :colourID, :lightType, :public, :state, :grouplight, :inviteAllowed, :postToSocialMedia, :lightSocialMediaId, :reoccurrence, 0, :description, :title)");
    $newLightQuery->bindParam(':userID', $userId);
    $newLightQuery->bindParam(':colourID', $requestColourType);
    $newLightQuery->bindParam(':lightType', $requestLightType, PDO::PARAM_INT);
    $newLightQuery->bindParam(':public', $requestPublic, PDO::PARAM_INT);
    $newLightQuery->bindParam(':state', $requestState, PDO::PARAM_INT);
    $newLightQuery->bindParam(':grouplight', $requestGroupLight, PDO::PARAM_INT);
    $newLightQuery->bindParam(':inviteAllowed', $requestInviteAllowed, PDO::PARAM_INT);
    $newLightQuery->bindParam(':postToSocialMedia', $requestPostToSocialMedia, PDO::PARAM_INT);
    $newLightQuery->bindParam(':lightSocialMediaId', $requestLightSocialMedia, PDO::PARAM_INT);
    $newLightQuery->bindParam(':reoccurrence', $requestReoccurrence, PDO::PARAM_INT);
    $newLightQuery->bindParam(':description', $requestDescription);
    $newLightQuery->bindParam(':title', $requestlightTitle);
    $newLightQuery->execute();
    
    
    //Get the new ID for the light as it is an auto increment field.
    // We will return this ID in a message back to the user
    $newLightId = $db->lastInsertId();
    $insertSuccess=true;
    echo "new light ID is $newLightId";
    
  }
  catch (PDOException $e){
    if ($e->getCode() == 1062) {
      
      $errMsg .= 'Key constrain violation.<br>'; 
    }
    else{
      throw $e;
    }
    
  }
  
  if(insertSuccess){
  	echo "Light Inserted \n";
  	echo "Light Inserted your light can be viewed at https://greenlight-drop-table-team-hypnotik.c9users.io/lights/$newLightId";
  	http_response_code(201); //201 = Created
  	
  }
  
	}
	else {
		//exit the function
		return;
	}

}

/*************************************************************************
 * PATCH /LIGHTS 
 * 
/***********************************************************************/

function patchLight()	{
	//Currently a patch only supports changing the light state.
	//include the database library
	include('config.php');
	
	//Get the body of the request sent to the site
	$input = json_decode(file_get_contents('php://input'),true);
	$requestState = $input['state'];
	echo "state is $requestState";
	
	//Get the lightId out of the URL and if its not numeric exit the function and give the user a helpful response.
	$url = $_GET['url'];
	$forwardslashIndex = strpos($url, "/");
	$lightId = substr($url, $forwardslashIndex + 1);
	
	if (!is_numeric($lightId)) {
	echo "Please only patch a numeric lights Id. Patch requests can only be provided for one light. \n Example URL : api/lights/1";
	http_response_code(403);
	return;
	}
	
	//Get the sessionID from the header
	$sessionId = $_SERVER['HTTP_SESSIONID'];
	
	//if the sessionID was not empty pass it to the checkSession function and if the session is valid we get the userID back from the function.
	if($sessionId != null){
		$userId = checkSession($sessionId);
	}
	else {
	//If sessionId is empty reject the request , exit the function and return a 401 (unauthorised).
		echo "Session ID is empty Please POST to the /login resource to recieve a sessionID";
		http_response_code(401); //401 = Unauthorised
		return false;
	}
	
	//If the state value is not a 0 or a 1 exit the function and provide a valid http response.
	if ($requestState != '1' || $requestState != '0') {
	
	echo "The State can only be a 1 (on) or 0 (off)";
	return false;
	}
	
	if ($userId != false) {
			
				$updateSuccess=false;

    			$updateLight = $db->prepare("UPDATE tblLights SET State = :state WHERE LightID = :lightId AND UserID = :userId");
    			$updateLight->bindParam(':state', $requestState, PDO::PARAM_INT);
    			$updateLight->bindParam(':lightId', $lightId, PDO::PARAM_INT);
    			$updateLight->bindParam(':userId', $userId, PDO::PARAM_INT);
    			$updateLight->execute();

    			$updateSuccess=true;
	}
	else {
		echo "invalid Session";
		return;
		
	}
	
	if (updateSuccess) {
  		echo "Light Updated \n";
  		echo "Your light  be viewed at https://greenlight-drop-table-team-hypnotik.c9users.io/lights/$lightId \n";
  		http_response_code(201); //201 = Created
	}
}
	
/*************************************************************************
 * GET /LIGHTS 
 * 
/***********************************************************************/


function getLights(){
	include('config.php');
	
	$sessionId = $_SERVER['HTTP_SESSIONID'];
	$lightId = $_SERVER['HTTP_LIGHTID'];
	
	/*
	* Public = all lights the user can see
	* Private = all private lights the user can see - Will be valuable when group lights are available
	* Personal = all lights owner by the user
	*/
	$visibility = $_SERVER['HTTP_VISIBILITY'];
	$visibility = strtolower($visibility);
    
    echo "\r\n".$sessionId."\r\n";
    
	if($sessionId != null and $lightId == null){
		$userId = checkSession($sessionId);
		
		if($visibility == 'public' or $visibility == null){
			if($userId != false){
				try{
					$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE (UserID = :userId OR Public = true) AND LightDeleted = false");
					$getLights->bindParam(':userId', $userId);
					$getLights->execute();
					
					$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($results as $result){
						echo "\nLight Found: ".print_r(array_values($result))."\n";
					}
					
					http_response_code(200);
					return json_encode($results);
				}
				catch(PDOException $e){
					echo "Error: " + $e->getMessage();   //Make better error
					return null;
				}
			}
			else{
				echo "UserId not found for specified session. Please create a session using createSessionToken";
				http_response_code(401);
				return null;
			}
		}
		else if($visibility == 'private'){
			if($userId != false){
				try{
					//Need to update when group lights are available
					$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE (UserID = :userId AND Public = false) AND LightDeleted = false");
					$getLights->bindParam(':userId', $userId);
					$getLights->execute();
					
					$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($results as $result){
						echo "\nLight Found: ".print_r(array_values($result))."\n";
					}
					
					http_response_code(200);
					return json_encode($results);
				}
				catch(PDOException $e){
					echo "Error: " + $e->getMessage();   //Make better error
					return null;
				}
			}
			else{
				echo "UserId not found for specified session. Please create a session using createSessionToken";
				http_response_code(401);
				return null;
			}
		}
		else if($visibility == 'personal'){
			if($userId != false){
				try{
					$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE UserID = :userId AND LightDeleted = false");
					$getLights->bindParam(':userId', $userId);
					$getLights->execute();
					
					$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($results as $result){
						echo "\nLight Found: ".print_r(array_values($result))."\n";
					}
					
					http_response_code(200);
					return json_encode($results);
				}
				catch(PDOException $e){
					echo "Error: " + $e->getMessage();   //Make better error
					return null;
				}
			}
			else{
				echo "UserId not found for specified session. Please create a session using createSessionToken";
				http_response_code(401);
				return null;
			}
		}
	}
	else if($sessionId == null and $lightId != null){
		if($visibility == 'public' or $visibility == null){
			try{
				$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE LightId = :lightId AND Public = true AND LightDeleted = false");
				$getLights->bindParam(':lightId', $lightId);
				$getLights->execute();
				
				$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($results as $result){
					echo "\nLight Found: ".print_r(array_values($result))."\n";
				}
				
				http_response_code(200);
				return json_encode($results);
			}
			catch(PDOException $e){
				echo "Error: " + $e->getMessage();   //Make better error
				return null;
			}
		}
		else if($visibility == 'private'){
			echo "Must provide a sessionId in order to find related private lights.";
		}
		else if($visibility == 'personal'){
			echo "Must provide a sessionId in order to find personal lights.";
		}
	}
	else if($sessionId != null and $lightId != null){
		$userId = checkSession($sessionId);
		
		if($visibility == 'public' or $visibility == null){
			if($userId != false){
				try{
					$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE ((UserID = :userId AND Public = false) OR Public = true) AND LightId = :lightId AND LightDeleted = false");
					$getLights->bindParam(':userId', $userId);
					$getLights->bindParam(':lightId', $lightId);
					$getLights->execute();
					
					$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($results as $result){
						echo "\nLight Found: ".print_r(array_values($result))."\n";
					}
					
					http_response_code(200);
					return json_encode($results);
				}
				catch(PDOException $e){
					echo "Error: " + $e->getMessage();   //Make better error
					return null;
				}
			}
			else{
				echo "UserId not found for specified session. Please create a session using createSessionToken";
				http_response_code(401);
				return null;
			}
		}
		else if($visibility == 'private'){
			if($userId != false){
				try{
					$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE UserID = :userId AND LightId = :lightId AND Public = false AND LightDeleted = false");
					$getLights->bindParam(':userId', $userId);
					$getLights->bindParam(':lightId', $lightId);
					$getLights->execute();
					
					$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($results as $result){
						echo "\nLight Found: ".print_r(array_values($result))."\n";
					}
					
					http_response_code(200);
					return json_encode($results);
				}
				catch(PDOException $e){
					echo "Error: " + $e->getMessage();   //Make better error
					return null;
				}
			}
			else{
				echo "UserId not found for specified session. Please create a session using createSessionToken";
				http_response_code(401);
				return null;
			}
		}
		else if($visibility == 'personal'){
			if($userId != false){
				try{
					$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE UserID = :userId AND LightId = :lightId AND LightDeleted = false");
					$getLights->bindParam(':userId', $userId);
					$getLights->bindParam(':lightId', $lightId);
					$getLights->execute();
					
					$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($results as $result){
						echo "\nLight Found: ".print_r(array_values($result))."\n";
					}
					
					http_response_code(200);
					return json_encode($results);
				}
				catch(PDOException $e){
					echo "Error: " + $e->getMessage();   //Make better error
					return null;
				}
			}
			else{
				echo "UserId not found for specified session. Please create a session using createSessionToken";
				http_response_code(401);
				return null;
			}
		}
	}
	else if($sessionId == null and $lightId == null){
		if($visibility == 'public' or $visibility == null){
			try{
				$getLights = $db->prepare("SELECT LightID, LightTitle, Description, ColourID, State FROM tblLights WHERE Public = true AND LightDeleted = false");
				$getLights->execute();
				
				$results = $getLights->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($results as $result){
					echo "\nLight Found: ".print_r(array_values($result))."\n";
				}
				
				http_response_code(200);
				return json_encode($results);
			}
			catch(PDOException $e){
				echo "Error: " + $e->getMessage();   //Make better error
				return null;
			}
		}
		else if($visibility == 'private'){
			echo "Must provide a sessionId in order to find related private lights.";
		}
		else if($visibility == 'personal'){
			echo "Must provide a sessionId in order to find personal lights.";
		}
	}
	else{
		echo "Session ID, LightID, and Visibility are empty or contain invalid values. Please POST to the /login resource to recieve a sessionID or provide a LightID";
		http_response_code(401);
		return null;
	}
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
			
			
			//get the userID from the PDO query results
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
 
 
//********
//   JSON RESPONSE FUNCTION
//********
 {
 	$result = array();
	$result[] = array('config1' => 'value1');
	$result[] = array('config2' => 'value2');
	//looks like the echo will output the JSON result to the body. So if the user is on a  website it will show on their page.
	
	echo json_encode($result);

 }
  
    
 ?>