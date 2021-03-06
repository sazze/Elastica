<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Filter_GeoDistanceTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testGeoPoint() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->create(array(), true);

		$type = $index->getType('test');

		// Set mapping
		$type->setMapping(array('point' => array('type' => 'geo_point')));


		// Add doc 1
		$doc1 = new elastica\Document(1,
			array(
				'name' => 'ruflin',
			)
		);

		$doc1->addGeoPoint('point', 17, 19);
		$type->addDocument($doc1);

		// Add doc 2
		$doc2 = new elastica\Document(2,
			array(
				'name' => 'ruflin',
			)
		);

		$doc2->addGeoPoint('point', 30, 40);
		$type->addDocument($doc2);


		$index->optimize();
		$index->refresh();

		// Only one point should be in radius
		$query = new elastica\Query();
		$geoFilter = new elastica\filter\GeoDistance('point', 30, 40, '1km');

		$query = new elastica\Query(new elastica\query\MatchAll());
		$query->setFilter($geoFilter);
		$this->assertEquals(1, $type->search($query)->count());

		// Both points should be inside
		$query = new elastica\Query();
		$geoFilter = new elastica\filter\GeoDistance('point', 30, 40, '40000km');
		$query = new elastica\Query(new elastica\query\MatchAll());
		$query->setFilter($geoFilter);
		$index->refresh();

		$this->assertEquals(2, $type->search($query)->count());
	}

	public function testSetLatitude()
	{
		$geoDistance = new elastica\filter\GeoDistance('point', 38.89859, -77.035971, '10mi');
		$returnValue = $geoDistance->setLatitude(38.89859);
		$this->assertInstanceOf('elastica\filter\GeoDistance', $returnValue);
	}
}
