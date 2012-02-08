<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_FuzzyTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testToArray() {
		$fuzzy = new elastica\query\Fuzzy();

		$fuzzy->addField('user', array('value' => 'Nicolas', 'boost' => 1.0));

		$expectedArray = array(
			'fuzzy' => array(
				'user' => array(
					'value' => 'Nicolas',
					'boost' => 1.0
				)
			)
		);

		$this->assertEquals($expectedArray, $fuzzy->toArray());
	}

	public function testQuery() {

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

		$query = new elastica\query\Fuzzy();
		$query->addField('name', array('value' => 'Baden'));

		$resultSet = $index->search($query);

		$this->assertEquals(2, $resultSet->count());
	}
}
