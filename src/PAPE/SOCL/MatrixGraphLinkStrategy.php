<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractGraphLinkStrategy;
use PAPE\SOCL\AbstractNode;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class represents an adjacence matrix of a graph links
*/
class MatrixGraphLinkStrategy extends AbstractGraphLinkStrategy{

	/*
	* @var array
	*/
	protected $linkMatrix;

	/**
	* Constructor
	*/
	public function __construct(){
		$this->linkMatrix = array();
	}

	/**
	* @see AbstractGraphLinkStrategy::buildLink()
	*/
	public function buildLink(AbstractNode $node1, AbstractNode $node2){
		if(! $this->hasLink($node1, $node2))
			$this->linkMatrix[$node1->getId()][$node2->getId()] = 1;
	}

	/**
	* @see AbstractGraphLinkStrategy::removeLink()
	*/
	public function removeLink(AbstractNode $node1, AbstractNode $node2){
		if($this->hasLink($node1, $node2))
			unset($this->linkMatrix[$node1->getId()][$node2->getId()]);
	}

	/**
	* @see AbstractGraphLinkStrategy::clearLinks()
	*/
	public function clearLinks(){
		$this->linkMatrix = array();
	}

	/**
	* @see AbstractGraphLinkStrategy::hasLink()
	*/
	public function hasLink(AbstractNode $node1, AbstractNode $node2){
		return isset($this->linkMatrix[$node1->getId()][$node2->getId()]);
	}

	/**
	* @see AbstractGraphLinkStrategy::getLinkedNodes()
	*/
	public function getLinkedNodes(AbstractNode $node){		

		$linkedIds = array();

		$outLinkedIds = $this->getOutLinkedNodes($node);
		$inlinkedIds = $this->getInLinkedNodes($node);


		$linkedIds = array_merge($outLinkedIds, $inlinkedIds);		

		//Remove duplicates in case of two nodes got links to both endpoints
		$linkedIds = array_unique($linkedIds);
		return $linkedIds;
	}

	/**
	* GET all nodes where there is a link sourcing at a given node
	* @param $node  			the node where originate links 
	* @return array
	*/
	protected function getOutLinkedNodes(AbstractNode $node){

		$nodeId = $node->getId();
		$linkedIds = array();
		if(isset($this->linkMatrix[$nodeId])){
			foreach ($this->linkMatrix[$nodeId] as $key => $value) {
					$linkedIds [] = $key;
			}
		}

		return $linkedIds;
	}

	/**
	* GET all nodes where there is a link ending at a given node
	* @param $node  			the node where end links 
	* @return array
	*/
	protected function getInLinkedNodes(AbstractNode $node){

		$nodeId = $node->getId();
		$linkedIds = array();
		foreach ($this->linkMatrix as $key => $value) {
			if($nodeId !== $key && isset($this->linkMatrix[$key][$nodeId]))
					$linkedIds [] = $key;
		}

		return $linkedIds;
	}


	/**
	* @see AbstractGraphLinkStrategy::removeLinksOf()
	*/
	public function removeLinksOf(AbstractNode $node){
		$nodeId = $node->getId();
		if(isset($this->linkMatrix[$nodeId]))
			unset($this->linkMatrix[$nodeId]);

		$outLinks = $this->getOutLinkedNodes($node);
		foreach ($outLinks as $key => $value) {
			unset($this->linkMatrix[$value][$nodeId]);
		}
		
	}

}