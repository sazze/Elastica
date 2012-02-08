<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_TermsTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testFilteredSearch() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->create(array(), true);
		$type = $index->getType('helloworld');

		$doc = new elastica\Document(1, array('name' => 'hello world'));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('name' => 'nicolas ruflin'));
		$type->addDocument($doc);
		$doc = new elastica\Document(3, array('name' => 'ruflin'));
		$type->addDocument($doc);


		$query = new elastica\query\Terms();
		$query->setTerms('name', array('nicolas', 'hello'));

		$index->refresh();

		$resultSet = $type->search($query);

		$this->assertEquals(2, $resultSet->count());

		$query->addTerm('ruflin');
		$resultSet = $type->search($query);

		$this->assertEquals(3, $resultSet->count());
	}

	public function testSetMinimum() {
		$key = 'name';
		$terms = array('nicolas', 'ruflin');
		$minimum = 2;

		$query = new elastica\query\Terms($key, $terms);
		$query->setMinimumMatch($minimum);

		$data = $query->toArray();
		$this->assertEquals($minimum, $data['terms']['minimum_match']);
	}

	public function testInvalidParams() {
		$query = new elastica\query\Terms();

		try {
			$query->toArray();
			$this->fail('Should throw exception because no key');
		} catch (elastica\exception\Invalid $e) {
			$this->assertTrue(true);
		}

	}
}
