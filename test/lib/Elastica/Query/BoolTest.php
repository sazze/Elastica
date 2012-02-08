<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_BoolTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testToArray() {
		$query = new elastica\query\Bool();

		$idsQuery1 = new elastica\query\Ids();
		$idsQuery1->setIds(1);

		$idsQuery2 = new elastica\query\Ids();
		$idsQuery2->setIds(2);

		$idsQuery3 = new elastica\query\Ids();
		$idsQuery3->setIds(3);

		$boost = 1.2;
		$minMatch = 2;

		$query->setBoost($boost);
		$query->setMinimumNumberShouldMatch($minMatch);
		$query->addMust($idsQuery1);
		$query->addMustNot($idsQuery2);
		$query->addShould($idsQuery3->toArray());

		$expectedArray = array(
			'bool' => array(
				'must' => array($idsQuery1->toArray()),
				'should' => array($idsQuery3->toArray()),
				'minimum_number_should_match' => $minMatch,
				'must_not' => array($idsQuery2->toArray()),
				'boost' => $boost,
			)
		);

		$this->assertEquals($expectedArray, $query->toArray());
	}

	public function testSearch() {
		$client = new elastica\Client();
		$index = new elastica\Index($client, 'test');
		$index->create(array(), true);

		$type = new elastica\Type($index, 'helloworld');

		$doc = new elastica\Document(1, array('id' => 1, 'email' => 'hans@test.com', 'username' => 'hans', 'test' => array('2', '3', '5')));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('id' => 2, 'email' => 'emil@test.com', 'username' => 'emil', 'test' => array('1', '3', '6')));
		$type->addDocument($doc);
		$doc = new elastica\Document(3, array('id' => 3, 'email' => 'ruth@test.com', 'username' => 'ruth', 'test' => array('2', '3', '7')));
		$type->addDocument($doc);

		// Refresh index
		$index->refresh();

		$boolQuery = new elastica\query\Bool();
		$termQuery1 = new elastica\query\Term(array('test' => '2'));
		$boolQuery->addMust($termQuery1);
		$resultSet = $type->search($boolQuery);

		$this->assertEquals(2, $resultSet->count());

		$termQuery2 = new elastica\query\Term(array('test' => '5'));
		$boolQuery->addMust($termQuery2);
		$resultSet = $type->search($boolQuery);

		$this->assertEquals(1, $resultSet->count());

		$termQuery3 = new elastica\query\Term(array('username' => 'hans'));
		$boolQuery->addMust($termQuery3);
		$resultSet = $type->search($boolQuery);

		$this->assertEquals(1, $resultSet->count());

		$termQuery4 = new elastica\query\Term(array('username' => 'emil'));
		$boolQuery->addMust($termQuery4);
		$resultSet = $type->search($boolQuery);

		$this->assertEquals(0, $resultSet->count());
	}
}
