<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Filter_TypeTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testSetType() {
		$typeFilter = new elastica\filter\Type();
		$returnValue = $typeFilter->setType('type_name');
		$this->assertInstanceOf('elastica\filter\Type', $returnValue);
	}

	public function testToArray() {
		$typeFilter = new elastica\filter\Type('type_name');

		$expectedArray = array(
			'type' => array('value' => 'type_name')
		);

		$this->assertEquals($expectedArray, $typeFilter->toArray());
	}

}
