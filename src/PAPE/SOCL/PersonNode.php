<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractNode;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class represents a person inside the social graph	
*/
class PersonNode extends AbstractNode
{
	/**
	* @var int
	*/
	private $id;

	/**
	* @var string
	*/
	private $firstName;

	/**
	* @var string
	*/
	private $surname;

	/**
	* @var string
	*/
	private $gender;

	/**
	* @var int
	*/
	private $age;


	/**
	*	Constructor
	*	@param 		$id 							the ID given to the person
	*	@param 		$firstName 						the firstname
	*	@param 		$surname 						the surname
	*	@param 		$gender 						the gender Male/Female
	*	@param 		$age 	 						the age
	*	@throws		\InvalidArgumentException 	 	When id is null or the gender is not within accepted values Male, Female
	*/
	public function __construct($id, $firstName, $surname, $gender, $age)
	{

		if($id === null)
			throw new \InvalidArgumentException("Person constructor must be given a valid ID parameter");
		if(! in_array(strtoupper($gender), array('MALE', 'FEMALE')))
			throw new \InvalidArgumentException("Person constructor must be given a valid gender parameter: Male or Female");

		$this->id = (int)$id;
		$this->firstName = $firstName;
		$this->surname = $surname;	
		$this->gender = $gender;
		$this->age = (int)$age;
	}

	/**
	*	@return the ID of the person
	*
	**/
	public function getId()
	{
		return $this->id;
	}

	/**
	*	@return the firstname of the person
	*
	**/
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	*	Firstname setter
	*	@param the new firstname of the person
	*
	**/
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	/**
	*	@return the surname of the person
	*
	**/
	public function getSurname()
	{
		return $this->surname;
	}

	/**
	*	Surname setter
	*	@param the new surname of the person
	*
	**/
	public function setSurname($surname)

	{
		$this->surname = $surname;
	}

	/**
	*	@return the gender of the person
	*
	**/
	public function getGender()

	{
		return $this->gender;
	}

	/**
	*	Gender setter
	*	@param the new gender of the person
	*
	**/
	public function setGender($gender)
	{
		if(! in_array(strtoupper($gender), array('MALE', 'FEMALE')))
			throw new \InvalidArgumentException("Cannot set the gender if not: Male or Female");
		$this->gender = $gender;
	}

	/**
	*	@return the age of the person
	*
	**/
	public function getAge()
	{
		return $this->age;
	}

	/**
	*	Age setter
	*	@param the new age of the person
	*
	**/
	public function setAge($age)
	{
		$this->age = (int)$age;
	}
}