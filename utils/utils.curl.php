<?php

// I generally build a class like this for the sake of 
// sending meaningful JSON back to an AJAX request.
// Basically it encapsulates a response code, headers from response
// and the body.
class ServiceResponse {
	public $httpCode;
	public $headers;
	public $body;

	function __construct($httpCode, $headers, $body) {
		$this->httpCode = $httpCode;
		$this->headers = $headers;		
		$this->body = json_decode($body);
	}
}

// This is the calling curl function. It takes the following input:
// * url - Url of service to call
// * action - POST / GET etc
// * cookies - an associative array of any cookies to passthru
// * username - Basic http username
// * password - Basic http password
// * header - associative array of any other headers you might want to send
// * payload - the data you wish to post / put / patch
function curlSingleRequest($url, $action, $cookies = null, $username = null, $password = null, $headers = null, $payload = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
	if (($action == 'PUT' || $action == 'POST') && $payload != null) {	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);                                                                
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);                                                                                                                                       
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($payload))                                                                       
		);	
	}
	
	// Set username and password for basic auth if required
	if ($username && $password) {
		curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);  
	}
	
	// Set headers if required
	if ($headers) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	
	// Set cookies in request if they exist
	if ($cookies) {
        curl_setopt($ch, CURLOPT_COOKIE, implode(';', $cookies));
    }

    $response = curl_exec($ch);
	
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	
    curl_close($ch);
	
	// Get Cookies from resulting request
	$result = new ServiceResponse($httpCode, getHeadersFromCurlResponse($header), $body);

    return $result;
}

// Parses curl response for headers
function getHeadersFromCurlResponse($headerContent) {
    $headers = array();
    $arrRequests = explode("\r\n\r\n", $headerContent);
    for ($index = 0; $index < count($arrRequests) -1; $index++) {
        foreach (explode("\r\n", $arrRequests[$index]) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'][0] = $line;
			}
            else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key][] = $value;
            }
        }
    }
    return $headers;
}

?>