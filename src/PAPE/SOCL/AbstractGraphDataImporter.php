<?php 
namespace PAPE\SOCL;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This abstract class represents a generic way of importer existant data(XML, JSON, REST API, WEB SERVICE, SQL, NoSQL, ...) inside a graph
*/
abstract class AbstractGraphDataImporter{
	/**
	*	Get all the imported nodes from the source
	*	@return array 	the nodes list
	*/
	abstract public function getNodes();

	/**
	* Get all the imported links from the source
	* @return $array 	the links list
	*/
	abstract public function getLinks();
}