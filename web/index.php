<?php


require_once(__DIR__.'/../vendor/autoload.php');

use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PAPE\SOCL\SocialGraphAPIControllerProvider;



$app = new Silex\Application();
$app['debug'] = true;

$app->mount('/', new SocialGraphAPIControllerProvider());


/// ---------------------- ERROR HANDLER ---------------------

$app->error(function(\Exception $e, $code) use($app){

			$response = array();
		    $response['data'] = array('error_message' => $e->getMessage(), 'error_code' => $code);
		    $response = $app['gserializer']->serialize($response, 'json');
		    $response = str_replace('\\', '', $response);
		    return $response;
});


/// --------------------- CORS HANDLER -----------------------
$app->match('/{url}', function(){

	$response = new Response();
	return $response;

})->method('OPTIONS')->assert('url','.+');


$app->after(function(Request $request, RESPONSE $response){
	$response->headers->set('Access-Control-Allow-Origin', '*');
	$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});



$app->run();