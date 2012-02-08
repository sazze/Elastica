<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Filter_NotTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testToArray() {
		$idsFilter = new elastica\filter\Ids();
		$idsFilter->setIds(12);
		$filter = new elastica\filter\Not($idsFilter);

		$expectedArray = array(
			'not' => array(
				'filter' => $idsFilter->toArray()
			)
		);

		$this->assertEquals($expectedArray, $filter->toArray());
	}
}
