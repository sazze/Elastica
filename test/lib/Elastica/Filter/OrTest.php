<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Filter_OrTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testAddFilter() {
		$filter = $this->getMockForAbstractClass('elastica\filter\Abstract_');
		$orFilter = new elastica\filter\Or_();
		$returnValue = $orFilter->addFilter($filter);
		$this->assertInstanceOf('elastica\filter\Or_', $returnValue);
	}

	public function testToArray() {
		$orFilter = new elastica\filter\Or_();

		$filter1 = new elastica\filter\Ids();
		$filter1->setIds('1');

		$filter2 = new elastica\filter\Ids();
		$filter2->setIds('2');

		$orFilter->addFilter($filter1);
		$orFilter->addFilter($filter2);


		$expectedArray = array(
			'or' => array(
					$filter1->toArray(),
					$filter2->toArray()
				)
			);

		$this->assertEquals($expectedArray, $orFilter->toArray());
	}

}
