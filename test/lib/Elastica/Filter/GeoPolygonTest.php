<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Filter_GeoPolygonTest extends PHPUnit_Framework_TestCase
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

		$index->refresh();

		// Only one point should be in polygon
		$query = new elastica\Query();
		$points = array(array(16, 16), array(16, 20), array(20, 20), array(20, 16), array(16, 16));
		$geoFilter = new elastica\filter\GeoPolygon('point', compact('points'));

		$query = new elastica\Query(new elastica\query\MatchAll());
		$query->setFilter($geoFilter);
		$this->assertEquals(1, $type->search($query)->count());

		// Both points should be inside
		$query = new elastica\Query();
		$points = array(array(16, 16), array(16, 40), array(40, 40), array(40, 16), array(16, 16));
		$geoFilter = new elastica\filter\GeoPolygon('point', compact('points'));

		$query = new elastica\Query(new elastica\query\MatchAll());
		$query->setFilter($geoFilter);

		$this->assertEquals(2, $type->search($query)->count());
	}
}
