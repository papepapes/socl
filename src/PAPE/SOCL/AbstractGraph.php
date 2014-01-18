<?php  

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractNodeStore;
use PAPE\SOCL\AbstractGraphLinkStrategy;
use PAPE\SOCL\AbstractGraphDataImporter;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This abstract class represents a generic graph
*   The graph uses a NodeStore and a LinkStrategy(Adjacency matrix or list)
* 	The graph can use a DataImporter to import already existant data inside the data structure
*/
abstract class AbstractGraph {

	/**
	* Constructor
	* @param $nodeStore 		a way of storing the nodes
	* @param $linkStrategy 		a way of representing links
	*/
	abstract public function __construct(AbstractNodeStore $nodeStore, AbstractGraphLinkStrategy $linkStrategy);

	/**
	* Build graph's nodes and links based on existant data
	* @param $importer 			the Data importer class used to import existant
	*/
	abstract public function buildGraphFromData(AbstractGraphDataImporter $importer);

}