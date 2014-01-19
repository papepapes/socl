<?php 

use PAPE\SOCL\SQLITEGraphDataImporter;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	PAPE\SOCL\SQLITEGraphDataImporter class tests	
*/
class SQLITEGraphDataImporterTest extends PHPUnit_Framework_TestCase{

	protected $dataImporter;

	protected function setUp(){
		$this->dataImporter = new SQLITEGraphDataImporter(__DIR__.'/../../data/original.data.db');
	}

	/**
	* @expectedException Exception 
	* @expectedExceptionMessage Failure to load an unexistant db file.
	*/
	public function testThrowsExceptionOnUnexistantDBFile(){
		$this->assertFalse(file_exists('unexistant.db'));
		$sqliteDataImporter = new SQLITEGraphDataImporter('unexistant.db');
	}
	
	public function testGetNodes(){		
		$nodes = $this->dataImporter->getNodes();
		$this->assertEquals(count($nodes), 20);

		$this->assertEquals($nodes[13]->getSurname(), 'Daly');
		$this->assertNotEquals(strtolower($nodes[9]->getGender()), 'female');
	}

	public function testGetLinks(){
		$links = $this->dataImporter->getLinks();

		$this->assertTrue(count($links) > 0);
		$this->assertTrue(isset($links[12][7]) && $links[12][7] === 1);
		$this->assertFalse(isset($links[18][16]));
	}
}