<?php 

namespace PAPE\SOCL;

use PACO\SOCL\AbstractGraphDataImporter;
use PACO\SOCL\PersonNode;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class provides a SQLITE Data importer inside a graph. It can read a valid SQLITE file and extracts nodes and links data from it.
*/
class SQLITEGraphDataImporter extends AbstractGraphDataImporter{

	/**
	* A PDO connection object
	* @var PDO
	*/
	protected $con;

	/**
	*   Constructor
	*	@param the sqlite db file to get data from
	*	@throws Exception When the given sqlite db file does not exist OR WHEN there a PDO exception
	*   @todo Validation of the SQLITE database structure
	*/
	public function __construct($dbFile){
		if(!file_exists($dbFile)){
			throw new Exception('Failure to load an unexistant db file.');
		}
		try{
		
			$this->con = new PDO('sqlite:'.$dbFile);
			$this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(Exception $e){
			throw $e;
		}
		
	}

	/**
	* @see AbstractGraphDataImporter::getNodes()
	*/
	public function getNodes(){
		$rslt= $this->con->query('SELECT * FROM person');
		$people = array();
		$i = 0;
		foreach ($rslt as $person) {
			$p = new PersonNode($person['id'], $person['first_name'], $person['surname'], $person['gender'], $person['age']);
			if($i === 0){
					$people[++$i] = $p;
	
			}else{
					$people[] = $p;
			}
		}

		return $people;
	}

	/**
	* @see AbstractGraphDataImporter::getLinks()
	*/
	public function getLinks(){
		$rslt= $this->con->query('SELECT * FROM friendship');
		$links = array();
		foreach ($rslt as $relation) {
			$links[$relation['person_id']][$relation['friend_id']] = 1;
		}

		return $links;
	}

}