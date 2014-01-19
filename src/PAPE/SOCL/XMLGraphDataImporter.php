<?php 
namespace PAPE\SOCL;

use PAPE\SOCL\AbstractGraphDataImporter;
use PAPE\SOCL\PersonNode;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class provides a XML Data importer inside a graph. It can read a valid XML file and extracts nodes and links data from it.
*/
class XMLGraphDataImporter extends AbstractGraphDataImporter{

	/**
	* @var string
	*/
	protected $xmlFile;

	/**
	*   Constructor
	*	@param the xml file to get data from
	*   @throws Exception When the given xml file does not exist
	*   @todo Validation of the XML content format
	*/
	public function __construct($xmlFile){
		if(!file_exists($xmlFile)){
			throw new \Exception('Failure to load an unexistant db file.');
		}
		$this->xmlFile = $xmlFile;
	}

	/**
	* @see AbstractGraphDataImporter::getNodes()
	*/
	public function getNodes(){
		if(!file_exists($this->xmlFile))
			return array();
		$data = simplexml_load_file($this->xmlFile);
		$nodes = array();
		$i = 0;
		foreach ($data as $people) {
			foreach ($people as $person) {
				$p = new PersonNode((int)$person->id->{0}, (string)$person->firstName->{0}, (string)$person->surname->{0}, (string)$person->gender->{0}, (int)$person->age->{0});
				if($i === 0){
					$nodes [++$i] = $p;
	
					}else{
						$nodes[] = $p;
				}
			}
		}

		return $nodes;
	}

	/**
	* @see AbstractGraphDataImporter::getLinks()
	*/
	public function getLinks(){
		if(!file_exists($this->xmlFile))
			return array();
		$data = simplexml_load_file($this->xmlFile);
		$links = array();
		foreach ($data as $people) {
			foreach ($people as $person) {
				$pId = (int)$person->id->{0};
				foreach ($person->friends as $friend) {
					foreach ($friend as $friendId) {			
						$links[$pId][(int)$friendId] = 1;
					}
				}
			}
		}
		
		return $links;
	}

}