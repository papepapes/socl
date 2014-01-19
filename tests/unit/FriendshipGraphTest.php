<?php 

use PAPE\SOCL\FriendshipGraph;
use PAPE\SOCL\PersonNode;
use PAPE\SOCL\ArrayNodeStore;
use PAPE\SOCL\MatrixGraphLinkStrategy;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	PAPE\SOCL\FriendshipGraph class tests	
*/
class FriendshipGraphTest extends PHPUnit_Framework_TestCase{

	protected $nodeStoreMock;
	protected $linkStrategyMock;
	protected $graphDataImporterMock;

	protected function setUp(){
		$this->nodeStoreMock = $this->getMockForAbstractClass('PAPE\SOCL\AbstractNodeStore');
		$this->linkStrategyMock = $this->getMockForAbstractClass('PAPE\SOCL\AbstractGraphLinkStrategy');
		$this->graphDataImporterMock = $this->getMockForAbstractClass('PAPE\SOCL\AbstractGraphDataImporter');

	}

	public function testBuildGraphFromData(){

		$this->graphDataImporterMock->expects($this->once())->method('getNodes')->will($this->returnValue(array()));
		$this->graphDataImporterMock->expects($this->once())->method('getLinks')->will($this->returnValue(array()));;

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->buildGraphFromData($this->graphDataImporterMock);

	}

	public function testAddPerson(){
		$person = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$this->nodeStoreMock->expects($this->once())->method('storeNode')->with($person);

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->addPerson($person);
	}


	public function testRemovePerson(){
		$person = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$this->nodeStoreMock->expects($this->once())->method('removeNode')->with($person);

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->removePerson($person);
	}

	public function testUpdatePerson(){
		$person = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$this->nodeStoreMock->expects($this->once())->method('updateNode')->with(1, $person);

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->updatePerson(1, $person);
	}

	public function testCountPeople(){
		$this->nodeStoreMock->expects($this->once())->method('countNodes')->will($this->returnValue(4));

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$this->assertEquals($friendshipGraph->countPeople(), 4);
	}

	public function testExistsPerson(){
		$person = new PersonNode(2, 'Mamadou', 'paco', 'Male', 31);
		$this->nodeStoreMock->expects($this->once())->method('hasNodeId')->with(2)->will($this->returnValue(true));

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$this->assertTrue($friendshipGraph->existsPerson($person));
	}

	public function testGetPersonById(){
		$person = new PersonNode(2, 'Mamadou', 'paco', 'Male', 31);
		$this->nodeStoreMock->expects($this->once())->method('getNode')->with(2)->will($this->returnValue($person));

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$this->assertEquals($friendshipGraph->getPersonById(2), $person);
	}

	public function testBuildFriendship(){
		$person1 = new PersonNode(2, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(4, 'Clara', 'nix', 'Female', 28);
		$this->linkStrategyMock->expects($this->at(0))->method('buildLink')->with($person1, $person2);
		$this->linkStrategyMock->expects($this->at(1))->method('buildLink')->with($person2, $person1);
		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->buildFriendship($person1, $person2);

	}

	public function testRemoveFriendship(){
		$person1 = new PersonNode(2, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(4, 'Clara', 'nix', 'Female', 28);
		$this->linkStrategyMock->expects($this->at(0))->method('removeLink')->with($person1, $person2);
		$this->linkStrategyMock->expects($this->at(1))->method('removeLink')->with($person2, $person1);
		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->removeFriendship($person1, $person2);
	}

	public function testExistsFriendship(){
		$person1 = new PersonNode(2, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(4, 'Clara', 'nix', 'Female', 28);
		$this->linkStrategyMock->expects($this->once())->method('hasLink')->with($person1, $person2)->will($this->returnValue(true));
		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$this->assertTrue($friendshipGraph->existsFriendship($person1, $person2));
	}

	public function testClearFriendships(){
		$this->linkStrategyMock->expects($this->once())->method('clearLinks');
		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$friendshipGraph->clearFriendships();
	}

	public function testGetFriendsOf(){
		$person1 = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(2, 'Felice', 'felice', 'Female', 28);
		$person3 = new PersonNode(3, 'Jean', 'jean', 'Male', 34);
		$person4 = new PersonNode(4, 'Michael', 'syde', 'Male', 30);

		$this->linkStrategyMock->expects($this->any())->method('getLinkedNodes')->with($person1)->will($this->returnValue(array(2,3,4)));
		$this->nodeStoreMock->expects($this->at(0))->method('getNode')->with(2)->will($this->returnValue($person2));
		$this->nodeStoreMock->expects($this->at(1))->method('getNode')->with(3)->will($this->returnValue($person3));
		$this->nodeStoreMock->expects($this->at(2))->method('getNode')->with(4)->will($this->returnValue($person4));

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$p1Friends = $friendshipGraph->getFriendsOf($person1);
		$this->assertEquals(count($p1Friends), 3);
		$this->assertContains($person2, $p1Friends);
		$this->assertContains($person3, $p1Friends);
		$this->assertContains($person4, $p1Friends);


	}

	public function testGetFriendsOfriendsOf(){
		$person1 = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(2, 'Felice', 'felice', 'Female', 28);
		$person3 = new PersonNode(3, 'Jean', 'jean', 'Male', 34);
		$person4 = new PersonNode(4, 'Michael', 'syde', 'Male', 30);

		$this->linkStrategyMock->expects($this->at(0))->method('getLinkedNodes')->with($person1)->will($this->returnValue(array(2)));
		$this->linkStrategyMock->expects($this->at(1))->method('getLinkedNodes')->with($person2)->will($this->returnValue(array(3,4)));
		$this->nodeStoreMock->expects($this->at(0))->method('getNode')->with(2)->will($this->returnValue($person2));
		$this->nodeStoreMock->expects($this->at(1))->method('getNode')->with(3)->will($this->returnValue($person3));
		$this->nodeStoreMock->expects($this->at(2))->method('getNode')->with(4)->will($this->returnValue($person4));

		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$p1FriendsOfriends = $friendshipGraph->getFriendsOfriendsOf($person1);
		$this->assertEquals(count($p1FriendsOfriends), 2);
		$this->assertContains($person3, $p1FriendsOfriends);
		$this->assertContains($person4, $p1FriendsOfriends);
	}

	public function testGetSuggestedFriendsOf(){
		$person1 = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(2, 'Felice', 'felice', 'Female', 28);
		$person3 = new PersonNode(3, 'Jean', 'jean', 'Male', 34);
		$person4 = new PersonNode(4, 'Michael', 'syde', 'Male', 30);
		$person5 = new PersonNode(5, 'Paul', 'Crowe', 'Male', 28);
		$person6 = new PersonNode(6, 'Michelle', 'Lane', 'Female', 24);

		 $store = new ArrayNodeStore(); //// Any class that extends AbstractNodeStore can be placed here
         $lnStr = new MatrixGraphLinkStrategy(); //// Any class that extends AbstractGraphLinkStrategy can be placed here
         $friendshipGraph = new FriendshipGraph($store, $lnStr);

         $friendshipGraph->addPerson($person1);
         $friendshipGraph->addPerson($person2);
         $friendshipGraph->addPerson($person3);
         $friendshipGraph->addPerson($person4);
         $friendshipGraph->addPerson($person5);
         $friendshipGraph->addPerson($person6);

         $friendshipGraph->buildFriendship($person1, $person2);
         $friendshipGraph->buildFriendship($person1, $person3);
         $friendshipGraph->buildFriendship($person2, $person5);
         $friendshipGraph->buildFriendship($person3, $person4);
         $friendshipGraph->buildFriendship($person2, $person4);
         $friendshipGraph->buildFriendship($person2, $person1);
         $friendshipGraph->buildFriendship($person3, $person6);

		$suggestedFriends = $friendshipGraph->getSuggestedFriendsOf($person1);

		$this->assertEquals(count($suggestedFriends), 1);
		$this->assertContains($person4, $suggestedFriends);
		$this->assertNotContains($person6, $suggestedFriends);
	}

	public function testGetPeople(){
		$person1 = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$person2 = new PersonNode(2, 'Felice', 'felice', 'Female', 28);
		$person3 = new PersonNode(3, 'Jean', 'jean', 'Male', 34);

		$this->nodeStoreMock->expects($this->once())->method('getNodes')->will($this->returnValue(array($person1, $person2, $person3)));
		$friendshipGraph = new FriendshipGraph($this->nodeStoreMock, $this->linkStrategyMock);
		$people = $friendshipGraph->getPeople();
		$this->assertEquals(count($people), 3);
		$this->assertContains($person1, $people);
		$this->assertContains($person2, $people);
		$this->assertContains($person3, $people);
	}
}