<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractGraph;
use PAPE\SOCL\AbstractNodeStore;
use PAPE\SOCL\AbstractGraphLinkStrategy;
use PAPE\SOCL\AbstractGraphDataImporter;
use PAPE\SOCL\PersonNode;
use PAPE\SOCL\Utils\PersonNodeExport;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class provides a SOCIAL GRAPH. Each person may have one or more friends inside the graph.
* 	Since the graph's links are about FRIENDSHIP the graph is then UNDIRECTED: which means that when 
* 	two persons P1 and P2 are linked, this class MUST link P1 to P2 and P2 to P1.
*	And when we need to remove relationship, this class MUST remove 2 links.
*/
class FriendshipGraph  extends AbstractGraph { 

	/**
	* @var ArrayNodeStore
	*/
	protected $nodeStore;

	/**
	* @var MatrixGraphLinkStrategy 
	*/
	protected $linkStrategy;

	/**
	* Constructor
	* @see AbstractGraph::__construct() 	
	*/
	public function __construct(AbstractNodeStore $nodeStore, AbstractGraphLinkStrategy $linkStrategy){
		$this->nodeStore = $nodeStore;
		$this->linkStrategy = $linkStrategy;
	}

	/**
	* @see AbstractGraph::buildGraphFromData()
	*/
	public function buildGraphFromData(AbstractGraphDataImporter $importer){
		$this->buildNodes($importer);
		$this->buildFriendships($importer);
	}


	/**
	* Save a person inside the graph
	* @param $person 	the person to save
	*/
	public function addPerson(PersonNode $person){
		$this->nodeStore->storeNode($person);
	}

	/**
	* Remove a person and his links in the graph
	* @param $person 	the person to remove
	*/
	public function removePerson(PersonNode $person){
		$this->nodeStore->removeNode($person);
		$this->linkStrategy->removeLinksOf($person);
	}

	/**
	* Update a person and his links in the graph
	* @param $person 	the person to update
	*/
	public function updatePerson($id, PersonNode $person){
		$this->nodeStore->updateNode($id, $person);
	}

	/**
	* Count the total number of people in the graph
	* @return int 
	*/
	public function countPeople(){
		return $this->nodeStore->countNodes();
	}

	/**
	* Check if a given person exists inside the graph
	* @param $person 		the one to check
	* @return boolean 
	*/
	public function existsPerson(PersonNode $person){
		return $this->nodeStore->hasNodeId($person->getId());
	}

	/**
	* Find a person inside the social graph by it's ID
	* @param $id 		the id of the person to lookup
	* @return PersonNode 
	*/
	public function getPersonById($id){
		return $this->nodeStore->getNode($id);
	}

	/**
	* Get all the people inside the social graph
	* @return array 
	*/
	public function getPeople(){
		return $this->nodeStore->getNodes();
	}

	/**
	* Build all nodes of the social graph based on existant data
	* @param $importer 		the AbstractGraphDataImporter used to import the nodes
	*/
	private function buildNodes(AbstractGraphDataImporter $importer){
		$people = $importer->getNodes();
		foreach ($people as $person) {
			$this->addPerson($person);
		}
	}

	/**
	* Build all links of the social graph based on existant data
	* @param $importer 		the AbstractGraphDataImporter used to import the links
	*/
	private function buildFriendships(AbstractGraphDataImporter $importer){
		$relationMatrix = $importer->getLinks();
		foreach ($relationMatrix as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				$person = $this->nodeStore->getNode($key1);
				$friend = $this->nodeStore->getNode($key2);
				$this->linkStrategy->buildLink($person, $friend);
			}
		}
	}


	/**
	* Make two people friends
	* @param $person1 				
	* @param $person2				person1's friend 		
	*/
	public function buildFriendship(PersonNode $person1, PersonNode $person2){
		if($person1 != $person2){			
			$this->linkStrategy->buildLink($person1, $person2);
			$this->linkStrategy->buildLink($person2, $person1);
		}
	}
 
 	/**
	* Remove friendship between two people; very sad moment :(
	* @param $person1 				
	* @param $person2						
	*/
	public function removeFriendship(PersonNode $person1, PersonNode $person2){
		$this->linkStrategy->removeLink($person1, $person2);
		$this->linkStrategy->removeLink($person2, $person1);
	}

	/**
	* Check if two people are friends
	* @param $person1 				
	* @param $person2						
	*/
	public function existsFriendship(PersonNode $person1, PersonNode $person2){
		// THEOREM: (in case of our implementation)
		// IF person1 is linked to person2 THEN person2 is linked to person1 too 
		// SO check only one direction 
		return $this->linkStrategy->hasLink($person1, $person2);
	}

	/**
	* Removes all relationships between people of our graph
	*/
	public function clearFriendships(){
		$this->linkStrategy->clearLinks();
	}

	/**
	* Get all direct friends of a person	
	* @param $person 		the person to lookup friends of
	* @return $array 		a list of person's friends
	*/
	public function getFriendsOf(PersonNode $person){
		$people = array();
		$friends = array();
		$people[] = $person;


		foreach ($people as $person) {
			$friendsIds = $this->linkStrategy->getLinkedNodes($person);
			for ($i=0; $i < count($friendsIds) ; $i++) { 
				$f = $this->nodeStore->getNode($friendsIds[$i]);
				$friends[] = $f;
			}
		}

		return $friends;

		//return $this->getFriendsOfLevel($people, 0);
	}

	/**
	* Get all friends of direct friends of a person	
	* @param $person 		the person to lookup friends of friends of
	* @param $keepDuplicates 		If set to TRUE the return will contains duplicates values if available
	* @return $array 		a list of person's friends's friends
	* @todo 				improve the algo
	*/
	public function getFriendsOfriendsOf(PersonNode $person, $keepDuplicates = false){
		$friends = array();
		$friendsOf = array();
	
		$friends = $this->getFriendsOf($person);

		foreach ($friends as $friend) {
			foreach ($this->getFriendsOf($friend) as $p) {
				$friendsOf[] = $p;
			}
		}
		$friendsOf = array_filter($friendsOf, function($friend) use($person, $friends, $friendsOf){
			return $friend != $person && !in_array($friend, $friends);
		});

		$friendsOf = array_values($friendsOf);

		if(!$keepDuplicates){
			$tmp = array();
			foreach ($friendsOf as $f) {
				if(!in_array($f, $tmp))
					$tmp[] = $f;
			}
	
			$friendsOf = $tmp;
		}

		return $friendsOf;

	}

	/**
	* Get all suggested friends of a person. 
	* DEFINITION: A suggested friend of mine = a person who has no direct relationship with me but who is directly related to at
	* least two of my direct friends
	* @param $person 		the person to lookup for suggested friends
	* @return $array 		a list of person's suggested friends
	* @todo 				improve the algo
	*/
	public function getSuggestedFriendsOf(PersonNode $person){
		$ffriends = $this->getFriendsOfriendsOf($person, true); 
		$ffriendIds = array();
		foreach ($ffriends as $friend) {
			$ffriendIds[] = $friend->getId();
		}
		$ffriendIds = array_count_values($ffriendIds);
		$ffriendIds = array_filter($ffriendIds, function($val){ return $val > 1;});

		$ffriends = array();
		foreach ($ffriendIds as $id => $cnt) {
			$ffriends[] = $this->nodeStore->getNode($id);
		}
		$ffriends = array_filter($ffriends, function($friend) use($person){
			return $friend != $person;
		});
		
		return $ffriends;
	}

	
	/**
	* Export all data inside the graph inside an array
	* @return array
	*/
	public function exportToArray(){
		$data = array();
		$people = $this->nodeStore->getNodes();

		foreach ($people as $person) {
			$dp = new PersonNodeExport();
			$dp->setId($person->getId());
			$dp->setFirstName($person->getFirstName());
			$dp->setSurname($person->getSurname());
			$dp->setGender($person->getGender());
			$dp->setAge($person->getAge());

			foreach ($this->linkStrategy->getLinkedNodes($person) as $lp) {
				$dp->addFriend($lp);
			}

			$data[] = $dp;
		}

		return $data;
	}

}