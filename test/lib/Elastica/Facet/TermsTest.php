<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';


class Elastica_Facet_TermsTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {

	}

	public function testQuery() {

		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->create(array(), true);
		$type = $index->getType('helloworld');

		$doc = new elastica\Document(1, array('name' => 'nicolas ruflin'));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('name' => 'ruflin test'));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('name' => 'nicolas helloworld'));
		$type->addDocument($doc);


		$facet = new elastica\facet\Terms('test');
		$facet->setField('name');

		$query = new elastica\Query();
		$query->addFacet($facet);
		$query->setQuery(new elastica\query\MatchAll());

		$index->refresh();

		$response = $type->search($query);
		$facets = $response->getFacets();

		$this->assertEquals(3, count($facets['test']['terms']));
	}
}
