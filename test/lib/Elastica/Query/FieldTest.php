<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_FieldTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testTextPhrase() {

		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->create(array(), true);
		$type = $index->getType('test');

		$doc = new elastica\Document(1, array('name' => 'Basel-Stadt'));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('name' => 'New York'));
		$type->addDocument($doc);
		$doc = new elastica\Document(3, array('name' => 'Baden'));
		$type->addDocument($doc);
		$doc = new elastica\Document(4, array('name' => 'Baden Baden'));
		$type->addDocument($doc);


		$index->refresh();

		$type = 'text_phrase';
		$field = 'name';

		$query = new elastica\query\Field();
		$query->setField('name');
		$query->setQueryString('"Baden Baden"');

		$resultSet = $index->search($query);

		$this->assertEquals(1, $resultSet->count());
	}

	public function testToArray() {
		$query = new elastica\query\Field('user', 'jack');
		$expected = array('field' => array('user' => array('query' => 'jack')));

		$this->assertSame($expected, $query->toArray());
	}
}
