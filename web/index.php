<?php


require_once(__DIR__.'/../vendor/autoload.php');

use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use PAPE\SOCL\SocialGraphAPIControllerProvider;


$app = new Silex\Application();
$app['debug'] = true;

$app->register(new CorsServiceProvider());


$app->before(function(Request $request){
	if(0 === strpos($request->headers->get('Content-Type'), 'application/json')){
		$data = json_decode($request->getContent(), true);
		$request->request->replace(is_array($data) ? $data: array());
	}
});

$app->after($app['cors']);
$app->after(function(Request $request, Response $response){
    $response->headers->set('Content-Type', 'application/json');
});


$app->mount('/', new SocialGraphAPIControllerProvider());

$app->get('/docs/api', function(){
			return file_get_contents(__DIR__.'/../docs/api/api-docs.json');
});

/// ---------------------- ERROR HANDLER ---------------------

$app->error(function(\Exception $e, $code) use($app){

			$response = array();
			$response['status'] = "error";
		    $response['data'] = array("message" => $e->getMessage());
		    $response = $app['gserializer']->serialize($response, 'json');
		    $response = str_replace('\\', '', $response);
		    return $response;
});



$app->run();