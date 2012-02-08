<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';


class Elastica_DocumentTest extends Elastica_Test
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testAdd() {
		$doc = new elastica\Document();
		$returnValue = $doc->add('key', 'value');
		$data = $doc->getData();
		$this->assertArrayHasKey('key', $data);
		$this->assertEquals('value', $data['key']);
		$this->assertInstanceOf('elastica\Document', $returnValue);
	}

	public function testAddFile()
	{
		$doc = new elastica\Document();
		$returnValue = $doc->addFile('key', '/dev/null');
		$this->assertInstanceOf('elastica\Document', $returnValue);
	}

	public function testAddGeoPoint()
	{
		$doc = new elastica\Document();
		$returnValue = $doc->addGeoPoint('point', 38.89859, -77.035971);
		$this->assertInstanceOf('elastica\Document', $returnValue);
	}

	public function testSetData()
	{
		$doc = new elastica\Document();
		$returnValue = $doc->setData(array('data'));
		$this->assertInstanceOf('elastica\Document', $returnValue);
	}
}
