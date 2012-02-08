<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';


class Elastica_ResultTest extends Elastica_Test
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testGetters() {
		// Creates a new index 'xodoa' and a type 'user' inside this index
		$typeName = 'user';

		$index = $this->_createIndex();
		$type = $index->getType($typeName);


		// Adds 1 document to the index
		$docId = 3;
		$doc1 = new elastica\Document($docId, array('username' => 'hans'));
		$type->addDocument($doc1);

		// Refreshes index
		$index->refresh();

		$resultSet = $type->search('hans');

		$this->assertEquals(1, $resultSet->count());

		$result = $resultSet->current();

		$this->assertInstanceOf('elastica\Result', $result);
		$this->assertEquals($index->getName(), $result->getIndex());
		$this->assertEquals($typeName, $result->getType());
		$this->assertEquals($docId, $result->getId());
		$this->assertGreaterThan(0, $result->getScore());
		$this->assertInternalType('array', $result->getData());
	}

	public function testGetIdNoSource() {
		// Creates a new index 'xodoa' and a type 'user' inside this index
		$indexName = 'xodoa';
		$typeName = 'user';

		$client = new elastica\Client();
		$index = $client->getIndex($indexName);
		$index->create(array(), true);
		$type = $index->getType($typeName);

		$mapping = new elastica\type\Mapping($type);
		$mapping->disableSource();
		$mapping->send();


		// Adds 1 document to the index
		$docId = 3;
		$doc1 = new elastica\Document($docId, array('username' => 'hans'));
		$type->addDocument($doc1);

		// Refreshes index
		$index->refresh();

		$resultSet = $type->search('hans');

		$this->assertEquals(1, $resultSet->count());

		$result = $resultSet->current();

		$this->assertEquals(array(), $result->getSource());
		$this->assertInstanceOf('elastica\Result', $result);
		$this->assertEquals($indexName, $result->getIndex());
		$this->assertEquals($typeName, $result->getType());
		$this->assertEquals($docId, $result->getId());
		$this->assertGreaterThan(0, $result->getScore());
		$this->assertInternalType('array', $result->getData());
	}
}
