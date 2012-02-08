<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';

/**
 * Tests the example code
 */
class Elastica_ExampleTest extends Elastica_Test
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testExample() {
		// Creates a new index 'xodoa' and a type 'user' inside this index
		$client = new elastica\Client();
		$index = $client->getIndex('elastica_test');
		$index->create(array(), true);

		$type = $index->getType('user');

		// Adds 1 document to the index
		$doc1 = new elastica\Document(1,
			array('username' => 'hans', 'test' => array('2', '3', '5'))
		);
		$type->addDocument($doc1);

		// Adds a list of documents with _bulk upload to the index
		$docs = array();
		$docs[] = new elastica\Document(2,
			array('username' => 'john', 'test' => array('1', '3', '6'))
		);
		$docs[] = new elastica\Document(3,
			array('username' => 'rolf', 'test' => array('2', '3', '7'))
		);
		$type->addDocuments($docs);

		// Refresh index
		$index->refresh();

		$resultSet = $type->search('rolf');
	}
}
