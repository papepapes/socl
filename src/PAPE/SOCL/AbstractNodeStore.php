<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractNodeStore;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This abstract class represents a way of storing nodes of the graph	
*/
abstract class AbstractNodeStore{

	/**
	* Retrieve a node with a given ID
	* @param $id 				the ID of the node to return
	* @return AbstractNode 		the node with the given ID
	*/
	abstract public function getNode($id);

	/**
	* Store a node
	* @param $node 				the node to store
	*/
	abstract public function storeNode(AbstractNode $node);

	/**
	* Remove a node
	* @param $node 				the node to remove
	*/
	abstract public function removeNode(AbstractNode $node);

	/**
	* Stored a changed node
	* @param $id 				the id to update
	* @param $node 				the node to update
	*/
	abstract public function updateNode($id, AbstractNode $node);

	/**
	* Clear the store
	*/
	abstract public function clearNodes();

	/**
	* Get the total count of nodes stored
	* @return int 				the number of stored nodes
	*/
	abstract public function countNodes();

	/**
	* Checks if the store contains a given node's ID
	* @param $id 				the node's to check
	*/
	abstract public function hasNodeId($id);

	/**
	* Checks if the store contains a given node
	* @param $node 				the node to check
	*/
	abstract public function hasNode(AbstractNode $node);

	/**
	* Get the all the nodes
	* @return array
	*/
	abstract public function getNodes();


}