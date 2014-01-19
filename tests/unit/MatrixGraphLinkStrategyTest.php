<?php 

use PAPE\SOCL\PersonNode;
use PAPE\SOCL\MatrixGraphLinkStrategy;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	PAPE\SOCL\MatrixGraphLinkStrategy class tests	
*/
class MatrixGraphLinkStrategyTest extends PHPUnit_Framework_TestCase{

	protected $linkMatrix;
	protected $person1;
	protected $person2;
	protected $person3;
	protected $person4;

	public function setUp(){
		$this->person1 = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
		$this->person2 = new PersonNode(2, 'Felice', 'felice', 'Female', 28);
		$this->person3 = new PersonNode(3, 'Jean', 'jean', 'Male', 34);
		$this->person4 = new PersonNode(4, 'Michael', 'syde', 'Male', 30);

		$this->linkMatrix = new MatrixGraphLinkStrategy();
	}

	public function testLinksBuildingAndRemoval(){
		$this->linkMatrix->buildLink($this->person3, $this->person1);
		$this->linkMatrix->buildLink($this->person3, $this->person2);
		$this->linkMatrix->buildLink($this->person1, $this->person4);
		$this->linkMatrix->buildLink($this->person2, $this->person4);
		$this->linkMatrix->buildLink($this->person2, $this->person1);
		$this->linkMatrix->buildLink($this->person1, $this->person2);

		$this->assertTrue($this->linkMatrix->hasLink($this->person3, $this->person1));
		$this->assertTrue($this->linkMatrix->hasLink($this->person3, $this->person2));
		$this->assertTrue($this->linkMatrix->hasLink($this->person1, $this->person4));
		$this->assertTrue($this->linkMatrix->hasLink($this->person2, $this->person4));
		$this->assertTrue($this->linkMatrix->hasLink($this->person2, $this->person1));
		$this->assertTrue($this->linkMatrix->hasLink($this->person1, $this->person2));

		$this->assertFalse($this->linkMatrix->hasLink($this->person1, $this->person3));
		$this->assertFalse($this->linkMatrix->hasLink($this->person2, $this->person3));
		$this->assertFalse($this->linkMatrix->hasLink($this->person4, $this->person1));
		$this->assertFalse($this->linkMatrix->hasLink($this->person4, $this->person2));

		$this->linkMatrix->removeLink($this->person3, $this->person1);
		$this->assertFalse($this->linkMatrix->hasLink($this->person3, $this->person1));
		$this->assertTrue($this->linkMatrix->hasLink($this->person1, $this->person3));

	}

	public function testClear(){
		$this->linkMatrix->buildLink($this->person3, $this->person1);
		$this->linkMatrix->buildLink($this->person3, $this->person2);
		$this->linkMatrix->buildLink($this->person1, $this->person4);

		$this->linkMatrix->clearLinks();
		$this->assertFalse($this->linkMatrix->hasLink($this->person3, $this->person1));
		$this->assertFalse($this->linkMatrix->hasLink($this->person3, $this->person2));
		$this->assertFalse($this->linkMatrix->hasLink($this->person1, $this->person4));

	}


	public function testGetLinkedNodes(){
		$this->linkMatrix->buildLink($this->person3, $this->person1);
		$this->linkMatrix->buildLink($this->person3, $this->person2);
		$this->linkMatrix->buildLink($this->person1, $this->person4);

		$p3relatedPersons = $this->linkMatrix->getLinkedNodes($this->person3);
		$p1relatedPersons = $this->linkMatrix->getLinkedNodes($this->person1);

		$this->assertEquals(count($p3relatedPersons), 2);
		$this->assertEquals(count($p1relatedPersons), 1);

		$this->assertEquals($p3relatedPersons[0], 1);
		$this->assertEquals($p3relatedPersons[1], 2);
		$this->assertEquals($p1relatedPersons[0], 4);
	}

	public function testRemoveLinksOf(){
		$this->linkMatrix->buildLink($this->person3, $this->person1);
		$this->linkMatrix->buildLink($this->person3, $this->person2);
		$this->linkMatrix->buildLink($this->person1, $this->person4);

		$this->linkMatrix->removeLinksOf($person1);

		$this->assertFalse($this->linkMatrix->hasLink($this->person3, $this->person1));
		$this->assertTrue($this->linkMatrix->hasLink($this->person3, $this->person2));
		$this->assertFalse($this->linkMatrix->hasLink($this->person1, $this->person4));
	}

}