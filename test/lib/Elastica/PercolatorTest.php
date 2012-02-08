<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';


class Elastica_PercolatorTest extends Elastica_Test
{
	public function testConstruct() {
		$index = $this->_createIndex();
		$percolator = new elastica\Percolator($index);

		$percolatorName = 'percotest';

		$query = new elastica\query\Term(array('field1' => 'value1'));
		$response = $percolator->registerQuery($percolatorName, $query);

		$data = $response->getData();

		$expectedArray = array(
			'ok' => true,
			'_type' => $index->getName(),
			'_index' => '_percolator',
			'_id' => $percolatorName,
			'_version' => 1
		);

		$this->assertEquals($expectedArray, $data);
	}

	public function testMatchDoc() {
		$this->markTestSkipped('There is a bug in ElasticSearch:  https://github.com/elasticsearch/elasticsearch/issues/763');

		$index = $this->_createIndex();
		$percolator = new elastica\Percolator($index);

		$percolatorName = 'percotest';

		$query = new elastica\query\Term(array('name' => 'ruflin'));
		$response = $percolator->registerQuery($percolatorName, $query);

		$this->assertTrue($response->isOk());
		$this->assertFalse($response->hasError());

		$doc1 = new elastica\Document();
		$doc1->add('name', 'ruflin');

		$doc2 = new elastica\Document();
		$doc2->add('name', 'nicolas');

		$index = new elastica\Index($index->getClient(), '_percolator');
		$index->refresh();

		$matches1 = $percolator->matchDoc($doc1);

		$this->assertTrue(in_array($percolatorName, $matches1));
		$this->assertEquals(1, count($matches1));

		$matches2 = $percolator->matchDoc($doc2);
		$this->assertEmpty($matches2);
	}
}
