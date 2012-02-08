<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_NestedTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testSetQuery() {
		$nested = new elastica\query\Nested();
		$path = 'test1';

		$queryString = new elastica\query\QueryString('test');
		$this->assertInstanceOf('elastica\query\Nested', $nested->setQuery($queryString));
		$this->assertInstanceOf('elastica\query\Nested', $nested->setPath($path));
		$expected = array(
			'nested' => array(
				'query' => $queryString->toArray(),
				'path' => $path,
			)
		);

		$this->assertEquals($expected, $nested->toArray());
	}
}
