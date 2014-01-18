<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractNode;
use PAPE\SOCL\AbstractNodeStore;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class represents a graph node storage in form of an array
*/
class ArrayNodeStore extends AbstractNodeStore {

	/**
	* @var array
	*/
	protected $nodeArray;

	/**
	* Constructor
	*/
	public function __construct(){
		$this->nodeArray = array();
	}

	/**
	* @see AbstractNodeStore::getNode()
	*/
	public function getNode($id){
		if($this->hasNodeId($id))
			return $this->nodeArray[$id];
		else
			return null;
	}

	/**
	* @see AbstractNodeStore::storeNode()
	*/
	public function storeNode(AbstractNode $node){
		if(! $this->hasNode($node))
			$this->nodeArray[$node->getId()] = $node;
	}

	/**
	* @see AbstractNodeStore::removeNode()
	*/
	public function removeNode(AbstractNode $node){
		if( $this->hasNode($node))
			unset($this->nodeArray[$node->getId()]);
	}

	/**
	* @see AbstractNodeStore::updateNode()
	*/
	public function updateNode($id, AbstractNode $node){
		if( $this->hasNodeId($id))
			$this->nodeArray[$id] = $node;
	}

	/**
	* @see AbstractNodeStore::clearNodes()
	*/
	public function clearNodes(){
		$this->nodeArray = array();
	}

	/**
	* @see AbstractNodeStore::countNodes()
	*/
	public function countNodes(){
		return count($this->nodeArray);
	}

	/**
	* @see AbstractNodeStore::getNodes()
	*/
	public function getNodes(){
		return $this->nodeArray;
	}

	/**
	* @see AbstractNodeStore::hasNodeId()
	*/
	public function hasNodeId($id){
		return isset($this->nodeArray[$id]);
	}

	/**
	* @see AbstractNodeStore::hasNode()
	*/
	public function hasNode(AbstractNode $node){
		return in_array($node, $this->nodeArray);
	}
}