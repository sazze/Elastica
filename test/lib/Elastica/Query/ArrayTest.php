<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_ArrayTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testToArray() {
		$testQuery = array('hello' => array('world'), 'name' => 'ruflin');
		$query = new elastica\query\Array_($testQuery);

		$this->assertEquals($testQuery, $query->toArray());
	}
}
