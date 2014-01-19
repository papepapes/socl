<?php 

require_once('XMLGraphDataImporter.php');

class XMLGraphDataImporterTest extends PHPUnit_Framework_TestCase{

	protected $dataImporter;

	protected function setUp(){
		$this->dataImporter = new XMLGraphDataImporter(__DIR__.'/../../data/original.data.xml');
	}

	/**
	* @expectedException Exception 
	* @expectedExceptionMessage Failure to load an unexistant db file.
	*/
	public function testTryToImportUnexistantFileWillThrowAnException(){
		$this->assertFalse(file_exists('unexistant.xml'));
		$jsonDataImporter = new XMLGraphDataImporter('unexistant.xml');
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