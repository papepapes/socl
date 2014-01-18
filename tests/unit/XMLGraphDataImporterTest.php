<?php 

require_once('XMLGraphDataImporter.php');

class XMLGraphDataImporterTest extends PHPUnit_Framework_TestCase{

	protected function setUp(){

	}

	public function testGetNodes(){
		$xmlDataImporter = new XMLGraphDataImporter('unexistant.xml');
		$nodes = $xmlDataImporter->getNodes();
		$this->assertEquals(count($nodes), 0);

		$xmlDataImporter = new XMLGraphDataImporter('data.xml');
		$nodes = $xmlDataImporter->getNodes();
		$this->assertEquals(count($nodes), 20);

		$this->assertEquals($nodes[13]->getSurname(), 'Daly');
		$this->assertNotEquals($nodes[9]->getGender(), 'female');
	}

	public function testGetLinks(){
		$xmlDataImporter = new XMLGraphDataImporter('unexistant.xml');
		$nodes = $xmlDataImporter->getLinks();

		$this->assertEquals(count($nodes), 0);

		$xmlDataImporter = new XMLGraphDataImporter('data.xml');
		$nodes = $xmlDataImporter->getLinks();

		$this->assertTrue(count($nodes) > 0);
		$this->assertTrue(isset($nodes[12][7]) && $nodes[12][7] === 1);
		$this->assertFalse(isset($nodes[18][16]));
	}
}