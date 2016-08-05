<?php 
/**
* login.php
* Create a new template named login
* and a page with that template
* for testing the endpoint
*/
include_once './rest/core/rest.php';
include_once './rest/languages/languages.php';
include_once './rest/data/data.php';
include_once './rest/login/errors.php';

use Rest\Errors\MethodNotAllowed as MethodNotAllowed;

use Rest\Request as Request;
use Rest\Response as Response;
use Rest\Method as Method;
use Rest\Header as Header;

use Login\Errors;
use Languages\Language as Language;
use Data\Data as Data;

$response = new Response();

$response->allowMethod(Method::POST);

$params = Request::params();

$segments = explode('/', $params['it']);

$requestUri = '';

foreach ($segments as $key => $segment) {
    if($key != 0 && $segment != '') {
        $requestUri = $requestUri . $segment . '/' ;
    }
}


//var_dump($_SERVER['REQUEST_METHOD']);
//var_dump(Request::isPost());

//var_dump($_SERVER);
//var_dump(Header::getBasicAuth());


if (!Request::isPost()) {

	$response->setError(MethodNotAllowed::error());

} else {

	$username = $params['username'] || $params['apiuser'];
	$password = $params['password'] || $params['apikey'];

	if (!$username || !$password) {

		// Basic Auth
		$username = Header::username();
		$password = Header::password();
	}

	if ((!isset($username) || $username == '') ||
		(!isset($password) || $password == '')) {

		$response->setError(Login\Errors\InvalidCredentials::error());

	} else  {

        $apiKey = \Processwire\wire('pages')->get("template=login")->key;
        $apiUser = "admin";

		if ($username == $apiKey && $password == $apiUser) {

				$response->output['data']['name'] = 'Tony';
				$response->output['data']['lastname'] = 'Stark';
				$response->output['data']['job'] = 'Ironman';

                $response->addArray(Data::content($requestUri));


        } else {

			$response->setError(Login\Errors\InvalidCredentials::error());
		}
	}
}

$response->addArray(Language::current());
$response->addArray(Data::allPages());


$headerParam = Header::get('origin');
$response->addArray(['_request_headers' => Header::getAll()]);
$response->addArray(['headerParam' => $headerParam]);

/*
Should render
{
  "data": {
    "name": "Tony",
    "lastname": "Stark",
    "job": "Ironman"
  },
  "_language": {
    "id": 1017,
    "code": "default",
    "name": "English"
  }
}
*/
$response->render();
