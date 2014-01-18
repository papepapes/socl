<?php 

namespace PAPE\SOCL;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Silex\ControllerProviderInterface;
use PAPE\SOCL\FriendshipGraphServiceProvider;
use PAPE\SOCL\GraphDataSerializerServiceProvider;
use PAPE\SOCL\JSONGraphDataImporter;
use PAPE\SOCL\PersonNode;

/**
*   @author GUEYE MAMADOU <papepapes@gmail.com>
*   This class is a Silex controller provider to handle all requests to the social graph API
*/
class SocialGraphAPIControllerProvider implements ControllerProviderInterface {

	public function connect(Application $app)
	{
		//registers used providers
		$app->register(new FriendshipGraphServiceProvider());
		$app->register(new GraphDataSerializerServiceProvider());
		
		//import existant data in json format
		$app['socl']->buildGraphFromData(new JSONGraphDataImporter(__DIR__.'/../../../data/data.json'));
		//$app['socl']->buildGraphFromData(new JSONGraphDataImporter('data.json'));

		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];


		$controllers->get('/api/v1/people', function(Application $app){
		    $people = $app['socl']->getPeople();
		    $response = array();
		    $response['data'] = $people;
		    $response = $app['gserializer']->serialize($response, 'json');
		 //    $jsonData = $app['twig']->render('data.tpl.json.twig', array(
			// 	'people' => $app['socl']->exportToArray(),
			// ));
			//$jsonData = $app['socl']->exportToArray();
			//$jsonData = $app['gserializer']->serialize($jsonData, 'json');
			//$jsonData = str_replace('\\', '', $jsonData);
			//file_put_contents('data.json', $jsonData);
			return $response;
		});
		
		$controllers->get('/api/v1/people/{id}', function($id, Application $app){
		    $people = $app['socl']->getPersonById($id);
		    $response = array();
		    $response['data'] = $people;
		    $response = $app['gserializer']->serialize($response, 'json');
		    return $response;
		});


		$controllers->post('/api/v1/people', function(Application $app, Request $request){
		    $person = new PersonNode($request->get('id'), $request->get('firstname'), $request->get('surname'), $request->get('gender'), $request->get('age') );
		
		    $app['socl']->addPerson($person);
		    $response = array();
		    $response['data'] = array('Location' => '/api/v1/people/'.$person->getId());
		    $response = $app['gserializer']->serialize($response, 'json');
		    $response = str_replace('\\', '', $response);
		    return new Response($response, 200);
		});
		
		$controllers->put('/api/v1/people/{id}', function($id, Application $app, Request $request){
		   
		    $person = $app['socl']->getPersonById($request->get('id'));
		    $person->setFirstName($request->get('firstname'));
		    $person->setSurname($request->get('surname'));
		    $person->setGender($request->get('gender'));
		    $person->setAge($request->get('age'));
		
		    $app['socl']->updatePerson($id, $person);
		
		    $response = array();
		    $response['data'] = array('Location' => '/api/v1/people/'.$person->getId());
		    $response = $app['gserializer']->serialize($response, 'json');
		    $response = str_replace('\\', '', $response);
		    return new Response($response, 200);
		   
		});

		$controllers->delete('/api/v1/people/{id}', function($id, Application $app){
		    $person = $app['socl']->getPersonById($id);
		    $app['socl']->removePerson($person);
		    return new Response('', 204);
		});
		
		
		$controllers->get('/api/v1/people/{id}/friends', function($id, Application $app){
		    $person = $app['socl']->getPersonById($id);
		    $friends = $app['socl']->getFriendsOf($person);
		
		    $response = array();
		    $response['data'] = $friends;
		    $response = $app['gserializer']->serialize($response, 'json');
		    return $response;
		
		});
		
		$controllers->get('/api/v1/people/{id}/friends/friends', function($id, Application $app){
		    $person = $app['socl']->getPersonById($id);
		    $friends = $app['socl']->getFriendsOfriendsOf($person);
		
		    $response = array();
		    $response['data'] = $friends;
		    $response = $app['gserializer']->serialize($response, 'json');
		    return $response;
		});

		$controllers->post('/api/v1/people/{pid}/friends/{fid}', function($pid, $fid, Application $app){
		    $person = $app['socl']->getPersonById($pid);
		    $friend = $app['socl']->getPersonById($fid);
		    $app['socl']->buildFriendship($person, $friend);
		
		    $response = array();
		    $response['data'] = array('Location' => '/api/v1/people/'.$person->getId().'/friends');
		    $response = $app['gserializer']->serialize($response, 'json');
		    $response = str_replace('\\', '', $response);
		    return new Response($response, 200);
		});
		
		$controllers->delete('/api/v1/people/{pid}/friends/{fid}', function($pid, $fid, Application $app){
		    $person = $app['socl']->getPersonById($pid);
		    $friend = $app['socl']->getPersonById($fid);
		
		    $app['socl']->removeFriendship($person, $friend);
		    return new Response('', 204);
		});

		$controllers->get('/api/v1/people/{pid}/friends/suggested', function($pid, Application $app){
		    $person = $app['socl']->getPersonById($pid);
			$suggestedFriends = $app['socl']->getSuggestedFriendsOf($person);
		    $response = array();
		    $response['data'] = $suggestedFriends;
		    $response = $app['gserializer']->serialize($response, 'json');
		    return $response;
		});

		return $controllers;
	}
}