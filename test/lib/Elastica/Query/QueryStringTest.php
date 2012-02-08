<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_QueryStringTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}


	public function testSearchMultipleFields() {
		$str = md5(rand());
		$query = new elastica\query\QueryString($str);

		$expected = array(
			'query' => $str
		);

		$this->assertEquals(array('query_string' => $expected), $query->toArray());

		$fields = array();
		$max = rand() % 10 + 1;
		for($i = 0; $i <  $max; $i++) {
			$fields[] = md5(rand());
		}

		$query->setFields($fields);
		$expected['fields'] = $fields;
		$this->assertEquals(array('query_string' => $expected), $query->toArray());

		foreach(array(false, true) as $val) {
			$query->setUseDisMax($val);
			$expected['use_dis_max'] = $val;

			$this->assertEquals(array('query_string' => $expected), $query->toArray());
		}
	}


	public function testSearch() {

		$client = new elastica\Client();
		$index = new elastica\Index($client, 'test');
		$index->create(array(), true);
		$index->getSettings()->setNumberOfReplicas(0);
		//$index->getSettings()->setNumberOfShards(1);

		$type = new elastica\Type($index, 'helloworld');

		$doc = new elastica\Document(1, array('email' => 'test@test.com', 'username' => 'hanswurst', 'test' => array('2', '3', '5')));
		$type->addDocument($doc);

		// Refresh index
		$index->refresh();

		$queryString = new elastica\query\QueryString('test*');
		$resultSet = $type->search($queryString);

		$this->assertEquals(1, $resultSet->count());
	}

	public function testSetDefaultOperator() {

		$operator = 'AND';
		$query = new elastica\query\QueryString('test');
		$query->setDefaultOperator($operator);

		$data = $query->toArray();

		$this->assertEquals($data['query_string']['default_operator'], $operator);
	}

	public function testSetDefaultField() {
		$default = 'field1';
		$query = new elastica\query\QueryString('test');
		$query->setDefaultField($default);

		$data = $query->toArray();

		$this->assertEquals($data['query_string']['default_field'], $default);
	}

	public function testSetQueryStringInvalid() {
		$query = new elastica\query\QueryString();
		try {
			$query->setQueryString(array());
			$this->fail('should throw exception because no string');
		} catch (elastica\exception\Invalid $e) {
			$this->assertTrue(true);
		}
	}
}
