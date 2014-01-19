<?php 

use PAPE\SOCL\JSONGraphDataImporter;

/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	PAPE\SOCL\JSONGraphDataImporter class tests	
*/
class JSONGraphDataImporterTest extends \PHPUnit_Framework_TestCase {

	protected $dataImporter;

	protected function setUp(){
		$this->dataImporter = new JSONGraphDataImporter(__DIR__.'/../../data/original.data.json');
	}


	/**
	* @expectedException Exception 
	* @expectedExceptionMessage Failure to load an unexistant db file.
	*/
	public function testTryToImportUnexistantFileWillThrowAnException(){
		$this->assertFalse(file_exists('unexistant.json'));
		$jsonDataImporter = new JSONGraphDataImporter('unexistant.json');
	}

	public function testGetNodes(){

		$nodes = $this->dataImporter->getNodes();

		$this->assertEquals(count($nodes), 20);

		$this->assertEquals($nodes[13]->getSurname(), 'Daly');
		$this->assertNotEquals(strtolower($nodes[9]->getGender()), 'female');

	}


	public function testGetLinks(){
		$nodes = $this->dataImporter->getLinks();

		$this->assertTrue(count($nodes) > 0);
		$this->assertTrue(isset($nodes[12][7]) && $nodes[12][7] === 1);
		$this->assertFalse(isset($nodes[18][16]));

	}

}