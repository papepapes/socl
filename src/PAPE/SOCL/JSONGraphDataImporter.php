<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractGraphDataImporter;
use PAPE\SOCL\PersonNode;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class provides a JSON Data importer inside a graph. It can read a valid JSON file and extracts nodes and links data from it.
*/
class JSONGraphDataImporter extends AbstractGraphDataImporter {

	/**
	* @var string
	*/
	protected $jsonFile;

	/**
	*   Constructor
	*	@param the json file to get data from
	*   @throws Exception When the given json file does not exist
	*   @todo Validation of the JSON content format
	*/
	public function __construct($jsonFile){
		if(!file_exists($jsonFile)){
			throw new \Exception('Failure to load an unexistant db file.');
		}
		$this->jsonFile = $jsonFile;
	}

	/**
	* @see AbstractGraphDataImporter::getNodes()
	*/
	public function getNodes(){
		if(!file_exists($this->jsonFile))
			return array();
		$data = json_decode(file_get_contents($this->jsonFile));
		$people = array();
		$i = 0;
		foreach ($data as $item) {
			$p = new PersonNode($item->id, $item->firstName, $item->surname, $item->gender, $item->age);
			if($i === 0){
				$people [++$i] = $p;

			}else{
				$people [] = $p;
			}
		}

		return $people;
	}

	/**
	* @see AbstractGraphDataImporter::getLinks()
	*/
	public function getLinks(){
		if(!file_exists($this->jsonFile))
			return array();
		$data = json_decode(file_get_contents($this->jsonFile));
		$links = array();
		foreach ($data as $item) {
			foreach ($item->friends as $friendId) {
				$links[$item->id][$friendId] = 1;
			}
		}

		return $links;
	}

}