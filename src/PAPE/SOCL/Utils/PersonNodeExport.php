<?php 

namespace PAPE\SOCL\Utils;


/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	Utility class used to serialize a node of the social graph and it's related nodes.
*   Each instance of this class contains the node infos and the ids of related nodes in the friends property.
*   Then a serializer can use this class to generate a .json file if the social graph data strcture needs to be persisted. 
*/
class PersonNodeExport{

	protected $id;
	protected $firstName;
	protected $surname;
	protected $gender;
	protected $age;
	protected $friends;

	public function __construct(){
		$this->id = -1;
		$this->firstName = '';
		$this->surname = '';
		$this->gender = '';
		$this->age = '';
		$this->friends = array();
	}


	public function getId(){
		return $this->id;
	}
	public function getFirstName(){
		return $this->firstName;
	}
	public function getSurname(){
		return $this->surname;
	}
	public function getGender(){
		return $this->gender;
	}
	public function getAge(){
		return $this->age;
	}

	public function getFriends(){
		return $this->friends;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function setFirstName($firstName){
		$this->firstName = $firstName;
	}

	public function setSurname($surname){
		$this->surname = $surname;
	}

	public function setGender($gender){
		$this->gender = $gender;
	}

	public function setAge($age){
		$this->age = $age;
	}

	public function addFriend($fid){
		$this->friends[] = $fid;
	}


}