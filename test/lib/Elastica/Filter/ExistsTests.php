<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Filter_ExistsTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testToArray() {
		$field = 'test';
		$filter = new elastica\filter\Exists($field);

		$expectedArray = array('exists' => array('field' => $field));
		$this->assertEquals($expectedArray, $filter->toArray());
	}

	public function testSetField() {
		$field = 'test';
		$filter = new elastica\filter\Exists($field);

		$this->assertEquals($field, $filter->getParam('field'));

		$newField = 'hello world';
		$this->assertInstanceOf('elastica\filter\Exists', $filter->setField($newField));

		$this->assertEquals($newField, $filter->getParam('field'));
	}
}
