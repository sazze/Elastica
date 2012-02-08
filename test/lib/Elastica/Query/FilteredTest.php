<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_FilteredTest extends PHPUnit_Framework_TestCase
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

		$doc = new elastica\Document(1, array('id' => 1, 'email' => 'test@test.com', 'username' => 'hanswurst', 'test' => array('2', '3', '5')));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('id' => 2, 'email' => 'test@test.com', 'username' => 'peter', 'test' => array('2', '3', '5')));
		$type->addDocument($doc);

		$queryString = new elastica\query\QueryString('test*');

		$filter1 = new elastica\filter\Term();
		$filter1->setTerm('username', 'peter');

		$filter2 = new elastica\filter\Term();
		$filter2->setTerm('username', 'qwerqwer');

		$query1 = new elastica\query\Filtered($queryString, $filter1);
		$query2 = new elastica\query\Filtered($queryString, $filter2);
		$index->refresh();

		$resultSet = $type->search($queryString);
		$this->assertEquals(2, $resultSet->count());

		$resultSet = $type->search($query1);
		$this->assertEquals(1, $resultSet->count());

		$resultSet = $type->search($query2);
		$this->assertEquals(0, $resultSet->count());
	}
}
