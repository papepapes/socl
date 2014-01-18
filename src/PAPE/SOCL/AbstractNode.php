<?php 

namespace PAPE\SOCL;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This abstract class represents a node inside the graph	
*/
abstract class AbstractNode
{
	/**
	* @return the ID of the node
	*/
	abstract public function getId();
}