<?php 

require_once('SQLITEGraphDataImporter.php');

class SQLITEGraphDataImporterTest extends PHPUnit_Framework_TestCase{


	public function testGetNodes(){		

		$sqliteDataImporter = new SQLITEGraphDataImporter('data.db');
		$nodes = $sqliteDataImporter->getNodes();
		$this->assertEquals(count($nodes), 20);

		$this->assertEquals($nodes[13]->getSurname(), 'Daly');
		$this->assertNotEquals($nodes[9]->getGender(), 'female');
	}

	/**
	*	@expectedException Exception
	*
	*/
	public function testThrowsExceptionOnUnexistantDBFile(){
		if(file_exists('unexistant.db'))
			unlink('unexistant.db');
		$sqliteDataImporter = new SQLITEGraphDataImporter('unexistant.db');
	}

	public function testGetLinks(){

		$sqliteDataImporter = new SQLITEGraphDataImporter('data.db');
		$nodes = $sqliteDataImporter->getLinks();

		$this->assertTrue(count($nodes) > 0);
		$this->assertTrue(isset($nodes[12][7]) && $nodes[12][7] === 1);
		$this->assertFalse(isset($nodes[18][16]));

	}
}