<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';


class Elastica_QueryTest extends Elastica_Test
{
	public function setUp() {
	}

	public function tearDown() {
	}


	public function testStringConversion() {
		$queryString = '{
			"query" : {
				"filtered" : {
				"filter" : {
					"range" : {
					"due" : {
						"gte" : "2011-07-18 00:00:00",
						"lt" : "2011-07-25 00:00:00"
					}
					}
				},
				"query" : {
					"text_phrase" : {
					"title" : "Call back request"
					}
				}
				}
			},
			"sort" : {
				"due" : {
				"reverse" : true
				}
			},
			"fields" : [
				"created", "assigned_to"
			]
			}';

		$query = new elastica\query\Builder($queryString);
		$queryArray = $query->toArray();

		$this->assertInternalType('array', $queryArray);

		$this->assertEquals('2011-07-18 00:00:00', $queryArray['query']['filtered']['filter']['range']['due']['gte']);
	}

	public function testRawQuery() {

		$textQuery = new elastica\query\Text();
		$textQuery->setField('title', 'test');

		$query1 = elastica\Query::create($textQuery);

		$query2 = new elastica\Query();
		$query2->setRawQuery(array('query' => array('text' => array('title' => 'test'))));

		$this->assertEquals($query1->toArray(), $query2->toArray());
	}

	public function testSetSort() {
		$index = $this->_createIndex();
		$type = $index->getType('test');

		$doc = new elastica\Document(1, array('name' => 'hello world'));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('firstname' => 'guschti', 'lastname' => 'ruflin'));
		$type->addDocument($doc);
		$doc = new elastica\Document(3, array('firstname' => 'nicolas', 'lastname' => 'ruflin'));
		$type->addDocument($doc);


		$queryTerm = new elastica\query\Term();
		$queryTerm->setTerm('lastname', 'ruflin');

		$index->refresh();

		$query = elastica\Query::create($queryTerm);

		// ASC order
		$query->setSort(array(array('firstname' => array('order' => 'asc'))));
		$resultSet = $type->search($query);
		$this->assertEquals(2, $resultSet->count());

		$first = $resultSet->current()->getData();
		$second = $resultSet->next()->getData();

		$this->assertEquals('guschti', $first['firstname']);
		$this->assertEquals('nicolas', $second['firstname']);


		// DESC order
		$query->setSort(array('firstname' => array('order' => 'desc')));
		$resultSet = $type->search($query);
		$this->assertEquals(2, $resultSet->count());

		$first = $resultSet->current()->getData();
		$second = $resultSet->next()->getData();

		$this->assertEquals('nicolas', $first['firstname']);
		$this->assertEquals('guschti', $second['firstname']);
	}

	public function testAddSort() {
		$query = new elastica\Query();
		$sortParam = array('firstname' => array('order' => 'asc'));
		$query->addSort($sortParam);

		$this->assertEquals($query->getParam('sort'), array($sortParam));
	}

	public function testSetRawQuery() {
		$query = new elastica\Query();

		$params = array('query' => 'test');
		$query->setRawQuery($params);

		$this->assertEquals($params, $query->toArray());
	}

	public function testSetFields() {
		$query = new elastica\Query();

		$params = array('query' => 'test');

		$query->setFields(array('firstname', 'lastname'));


		$data = $query->toArray();

		$this->assertContains('firstname', $data['fields']);
		$this->assertContains('lastname', $data['fields']);
		$this->assertEquals(2, count($data['fields']));
	}

	public function testGetQuery() {
		$query = new elastica\Query();

		try {
			$query->getQuery();
			$this->fail('should throw exception because query does not exist');
		} catch(elastica\exception\Invalid $e) {
			$this->assertTrue(true);
		}


		$termQuery = new elastica\query\Term();
		$termQuery->setTerm('text', 'value');
		$query->setQuery($termQuery);

		$this->assertEquals($termQuery->toArray(), $query->getQuery());
	}
}
