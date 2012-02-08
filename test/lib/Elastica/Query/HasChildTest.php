<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_HasChildTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testToArray() {
		$ids = new elastica\query\Ids();
		$ids->setIds(12);

		$type = 'test';

		$query = new elastica\query\HasChild($ids, $type);
		$query->setType($type);

		$expectedArray = array(
			'has_child' => array(
				'type' => $type,
				'query' => $ids->toArray(),
			)
		);

		$this->assertEquals($expectedArray, $query->toArray());
	}
}
