<?php 

use PAPE\SOCL\PersonNode;
use PAPE\SOCL\ArrayNodeStore;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	PACO\SOCL\ArrayNodeStore class tests	
*/
class ArrayNodeStoreTest extends PHPUnit_Framework_TestCase {

	protected $nodeStore;
	protected $person1;
	protected $person2;
	protected $person3;
	protected $person4;

	public function setUp(){
		$this->person1 = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$this->person2 = new PersonNode(2, 'Felice', 'felice', 'Female', 28);
		$this->person3 = new PersonNode(3, 'Jean', 'jean', 'Male', 34);
		$this->person4 = new PersonNode(4, 'Michael', 'syde', 'Male', 30);

		$this->nodeStore = new ArrayNodeStore();
	}

	public function testStoreAndContainsNode(){
		$this->nodeStore->storeNode($this->person1);
		$this->assertEquals($this->nodeStore->getNode(1), $this->person1);
		$this->assertTrue($this->nodeStore->hasNodeId(1));
		$this->assertTrue($this->nodeStore->hasNode($this->person1));
	}

	public function testRemoveAndCountNode(){
		$this->nodeStore->storeNode($this->person1);
		$this->nodeStore->storeNode($this->person2);
		$this->nodeStore->removeNode($this->person1);
		$this->assertEquals($this->nodeStore->countNodes(), 1);
		$this->assertFalse($this->nodeStore->hasNodeId(1));
		$this->assertFalse($this->nodeStore->hasNode($this->person1));
	}

	public function testUpdateNode(){
		$this->nodeStore->storeNode($this->person1);
		$this->person1->setSurname('franco');
		$this->nodeStore->updateNode($this->person1->getId(), $this->person1);
		$this->assertNotNull($this->nodeStore->getNode(1));
		$this->assertEquals($this->nodeStore->getNode(1)->getSurname(), 'franco');
	}

	public function testClearAndCount(){
		$this->nodeStore->storeNode($this->person1);
		$this->nodeStore->storeNode($this->person2);
		$this->nodeStore->storeNode($this->person3);
		$this->nodeStore->storeNode($this->person4);
		$this->assertEquals($this->nodeStore->countNodes(), 4);
		$this->nodeStore->clearNodes();
		$this->assertEquals($this->nodeStore->countNodes(), 0);
	}

	public function testGetNodes(){
		$this->nodeStore->storeNode($this->person1);
		$this->nodeStore->storeNode($this->person2);
		$this->nodeStore->storeNode($this->person3);
		$this->nodeStore->storeNode($this->person4);

		$nodes = $this->nodeStore->getNodes();

		$this->assertEquals(count($nodes), 4);
		$this->assertContains($this->person1, $nodes);
		$this->assertContains($this->person2, $nodes);
		$this->assertContains($this->person3, $nodes);
		$this->assertContains($this->person4, $nodes);
	}

}