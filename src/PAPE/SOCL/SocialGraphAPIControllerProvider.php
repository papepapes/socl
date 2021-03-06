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


//use PAPE\SOCL\XMLGraphDataImporter;
//use PAPE\SOCL\SQLITEGraphDataImporter;


/**
*   @author GUEYE MAMADOU <papepapes@gmail.com>
*   This class is a Silex controller provider to handle all requests to the social graph API
*/
class SocialGraphAPIControllerProvider implements ControllerProviderInterface {

	public function connect(Application $app)
	{
		
		//registers used providers
		$app->register(new GraphDataSerializerServiceProvider());
		$app->register(new FriendshipGraphServiceProvider());
				
		// ------------------  JSON DATA IMPORTER used by default --------------------------------
		//import existant data in json format: use a local json data file if exists or use the original
		// this allows to persist data inside a local json file and use them in next requests
		if(!file_exists('data.json')){
		  $app['socl']->buildGraphFromData(new JSONGraphDataImporter(__DIR__.'/../../../data/original.data.json'));
		}else{
		  $app['socl']->buildGraphFromData(new JSONGraphDataImporter('data.json'));
		}
		
		
		// ------------------- XML DATA Importer: Comment the above importer and Remove theses comments to use
		/*if(!file_exists('data.xml')){
		  $app['socl']->buildGraphFromData(new XMLGraphDataImporter(__DIR__.'/../../../data/original.data.xml'));
		}else{
		  $app['socl']->buildGraphFromData(new XMLGraphDataImporter('data.xml'));
		}
		*/
		
		
		// ------------------- SQLITE DATA Importer: Comment the above importer and Remove theses comments to use
		/*if(!file_exists('data.db')){
		  $app['socl']->buildGraphFromData(new SQLITEGraphDataImporter(__DIR__.'/../../../data/original.data.db'));
		}else{
		  $app['socl']->buildGraphFromData(new SQLITEGraphDataImporter('data.db'));
		}
		*/
		
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];
		

		$controllers->get('/api/v1/people', function(Application $app){

		    $people = $app['socl']->getPeople();
		    $response = array();
		    $response['status'] = "success";
		    $response['data'] = count($people) === 0 ? '': $people ;
		    $response = $app['gserializer']->serialize($response, 'json');
			
			return new Response($response, 200);
		});
		
		$controllers->get('/api/v1/people/{id}', function($id, Application $app){

		    $person = $app['socl']->getPersonById($id);
		    $response = array();
		    $response['status'] = "success";
		    $response['data'] = $person === null ? '': $person;
		    $response = $app['gserializer']->serialize($response, 'json');

		    return new Response($response, 200);
		});


		$controllers->post('/api/v1/people', function(Application $app, Request $request){

			$data = $request->request;
			if(!$data->get('id') || !$data->get('firstname') || !$data->get('surname') || !$data->get('gender') || !$data->get('age')){
				return new Response('{"status": "error", "message": "a valid Person must have an id, a firstname, a surname, a gender and an age"}', 400);
			}
			if(!in_array(strtolower($data->get('gender')), array('male', 'female')) )
				return new Response('{"status": "error", "message": "the person\'s genre must be male or female"}', 400);

			$person = $app['socl']->getPersonById($data->get('id'));
			if(null !== $person){
				return new Response('{"status": "error", "message": "the person\'s id already exists."}', 400);
			}

			
		    $person = new PersonNode($data->get('id'), $data->get('firstname'), $data->get('surname'), $data->get('gender'), $data->get('age'));
		
		    $app['socl']->addPerson($person);
		    $response = array();
		    $response['status'] = "success";
		    $response['data'] = $person;
		    $response = $app['gserializer']->serialize($response, 'json');

		    $this->persistChanges($app);

		    return new Response($response, 200);

		});
		
		$controllers->put('/api/v1/people/{id}', function($id, Application $app, Request $request){

			$data = $request->request;
	   
		    $person = $app['socl']->getPersonById($id);
		    $response = array();

		    if(null !== $person){
		    	if(null !== $data->get('firstname'))
		    	    $person->setFirstName($data->get('firstname'));
		    	if(null !== $data->get('surname'))
		    		$person->setSurname($data->get('surname'));
		    	if(null !== $data->get('gender'))
		    		$person->setGender($data->get('gender'));
		    	if(null !== $data->get('age'))
		    	    $person->setAge($data->get('age'));
			
			    	$app['socl']->updatePerson($id, $person);	
			    	$response['data'] = $person;
		    	$this->persistChanges($app);
		    }
		    
		
		   
		    $response['status'] = "success";
		   
		    $response = $app['gserializer']->serialize($response, 'json');

		    

		    return new Response($response, 200);
		   
		});

		$controllers->delete('/api/v1/people/{id}', function($id, Application $app){
		    $person = $app['socl']->getPersonById($id);
		    if(null !== $person){
		    	$app['socl']->removePerson($person);
		    	$this->persistChanges($app);
		    }
		   
		    $response = array();
		    $response['status'] = "success";
		    $response = $app['gserializer']->serialize($response, 'json');


		    return new Response($response, 200);
		});
		
		
		$controllers->get('/api/v1/people/{id}/friends', function($id, Application $app){
		    $person = $app['socl']->getPersonById($id);
		    $response = array();
		    if(null !== $person){
		    	$friends = $app['socl']->getFriendsOf($person);
		    	$response['data'] = $friends;
		    }		
		    
		    $response['status'] = "success";
		   
		    $response = $app['gserializer']->serialize($response, 'json');
		    return new Response($response, 200);
		
		});
		
		$controllers->get('/api/v1/people/{id}/friends/friends', function($id, Application $app){
		    $person = $app['socl']->getPersonById($id);
		    $response = array();
		    if(null !== $person){
		    	$friends = $app['socl']->getFriendsOfriendsOf($person);
		    	$response['data'] = $friends;
		    }
		
		    $response = array();
		    $response['status'] = "success";
		    $response = $app['gserializer']->serialize($response, 'json');


		    return new Response($response, 200);
		});

		$controllers->post('/api/v1/people/{pid}/friends/{fid}', function($pid, $fid, Application $app){
		    $person = $app['socl']->getPersonById($pid);
		    $response = array();
			if(null !== $person){
		    	$friend = $app['socl']->getPersonById($fid);
		    	$app['socl']->buildFriendship($person, $friend);
		    	$response['data'] = array($person, $friend);
		    	$this->persistChanges($app);
				
			}

		
		    $response['status'] = "success";
		    $response = $app['gserializer']->serialize($response, 'json');


		    return new Response($response, 200);
		});
		
		$controllers->delete('/api/v1/people/{pid}/friends/{fid}', function($pid, $fid, Application $app){
		    $person = $app['socl']->getPersonById($pid);
		    $response = array();
		    if(null !== $person){
		    	$friend = $app['socl']->getPersonById($fid);
		    	$app['socl']->removeFriendship($person, $friend);
		    	$this->persistChanges($app);
		    	
		    }
		

		    $response['status'] = "success";
		    $response = $app['gserializer']->serialize($response, 'json');


		    return new Response($response, 200);
		});

		$controllers->get('/api/v1/people/{pid}/friends/suggested', function($pid, Application $app){
		    $person = $app['socl']->getPersonById($pid);
		    $response = array();

		    if(null !== $person){
				$suggestedFriends = $app['socl']->getSuggestedFriendsOf($person);
		    	$response['data'] = $suggestedFriends;		    	
		    }

		    
		    $response['status'] = "success";
		    $response = $app['gserializer']->serialize($response, 'json');

		    return new Response($response, 200);
		});

		
		return $controllers;
	}


	private function persistChanges(Application $app){

		$jsonData = $app['socl']->exportToArray();
		$jsonData = $app['gserializer']->serialize($jsonData, 'json');
		file_put_contents('data.json', $jsonData);
	}
}