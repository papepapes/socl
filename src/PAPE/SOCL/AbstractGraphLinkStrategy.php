<?php 

namespace PAPE\SOCL;

use PAPE\SOCL\AbstractNode;


/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This abstract class represents a generic way of representing a graph's relationships
*/
abstract class AbstractGraphLinkStrategy {

	/**
	* Register a directed link (an edge) between two existing nodes	
	* @param $node1			the node from which originates the link	
	* @param $node2			the node when ends the link	
	*/
	abstract public function buildLink(AbstractNode $node1, AbstractNode $node2);

	/**
	* Remove a directed link (an edge) between two existing nodes	
	* @param $node1			the node from which originates the link	
	* @param $node2			the node when ends the link	
	*/
	abstract public function removeLink(AbstractNode $node1, AbstractNode $node2);

	/**
	* Clear all existing links between nodes	
	*/
	abstract public function clearLinks();

	/**
	* Check the existance of a link (an edge) between two existing nodes	
	* @param $node1			the node from which originates the link	
	* @param $node2			the node when ends the link	
	* @return boolean
	*/
	abstract public function hasLink(AbstractNode $node1, AbstractNode $node2);

	/**
	* Get all nodes related to a given node. All nodes where points a link from this given node, or from which originates a link to 
	* this given node are returned
	* @param $node			the node from which originates the link	
	* @return array
	*/
	abstract public function getLinkedNodes(AbstractNode $node);

	/**
	* Remove all links involving a given $node
	* @param $node			the node whose links need to be removed	
	*/
	abstract public function removeLinksOf(AbstractNode $node);
}