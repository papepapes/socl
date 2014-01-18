<?php 

use PAPE\SOCL\PersonNode;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	PACO\SOCL\PersonNode class tests	
*/
class PersonNodeTest extends \PHPUnit_Framework_TestCase{

	protected $personNode;

	protected function setUp(){
		$this->personNode = new PersonNode(1, 'Mamadou', 'paco', 'Male', 31);
	}

	public function testCanGetId(){
		$this->assertEquals($this->personNode->getId(), 1);
	}

	public function testCanGetFirstName(){
		$this->assertEquals($this->personNode->getFirstName(), 'Mamadou');
	}
	public function testCanGetSurname(){
		$this->assertEquals($this->personNode->getSurname(), 'paco');
	}
	public function testCanGetGender(){
		$this->assertEquals($this->personNode->getGender(), 'Male');
	}
	public function testCanGetAge(){
		$this->assertEquals($this->personNode->getAge(), 31);
	}

	public function testCanSetFirstName(){
		$this->personNode->setFirstName('Albert');
		$this->assertEquals($this->personNode->getFirstName(), 'Albert');
	}

	public function testCanSetSurname(){
		$this->personNode->setSurname('pape');
		$this->assertEquals($this->personNode->getSurname(), 'pape');
	}
	public function testCanSetGender(){
		$this->personNode->setGender('Female');
		$this->assertEquals($this->personNode->getGender(), 'Female');
	}
	public function testCanSetAge(){
		$this->personNode->setAge(32);
		$this->assertEquals($this->personNode->getAge(), 32);
	}

	/**
	* @expectedException \Exception
	* @expectedExceptionMessage Person constructor must be given a valid ID parameter
	*/
	public function testThrowsExceptionWhenIdIsNull(){
		$person = new PersonNode(null, 'Mamadou', 'paco', 'Male', 31);
	}
	/**
	* @expectedException \Exception
	* @expectedExceptionMessage Person constructor must be given a valid gender parameter: Male or Female
	*/
	public function testThrowsExceptionWhenGenderIsNotMaleNeitherFemale(){
		$person = new PersonNode(1, 'Mamadou', 'paco', 'X', 31);
	}

	/**
	* @expectedException \Exception
	* @expectedExceptionMessage Cannot set the gender if not: Male or Female
	*/
	public function testCannotSetAGenderWhenValueIsNotMaleNeitherFemale(){
		$this->personNode->setGender('X');
	}
}
