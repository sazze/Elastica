<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';


class Elastica_ClusterTest extends Elastica_Test
{

	public function testGetNodeNames() {
		$client = new elastica\Client();

		$cluster = new elastica\Cluster($client);

		$names = $cluster->getNodeNames();

		$this->assertInternalType('array', $names);
		$this->assertGreaterThan(0, count($names));
	}

	public function testGetNodes() {
		$client = new elastica\Client();
		$cluster = $client->getCluster();

		$nodes = $cluster->getNodes();

		foreach($nodes as $node) {
			$this->assertInstanceOf('elastica\Node', $node);
		}

		$this->assertGreaterThan(0, count($nodes));
	}

	public function testGetState() {
		$client = new elastica\Client();
		$cluster = $client->getCluster();
		$state = $cluster->getState();
		$this->assertInternalType('array', $state);
	}

	public function testShutdown() {
		$this->markTestSkipped('This test shuts down the cluster which means the following tests would not work');
		$client = new elastica\Client();
		$cluster = $client->getCluster();

		$cluster->shutdown('2s');

		sleep(5);

		try {
			$client->getStatus();
			$this->fail('Should throw exception because cluster is shut down');
		} catch(elastica\exception\Client $e) {
			$this->assertTrue(true);
		}
	}

	public function testGetIndexNames() {
		$client = new elastica\Client();
		$cluster = $client->getCluster();

		$indexName = 'elastica_test999';
		$index = $this->_createIndex($indexName);
		$index->delete();
		$cluster->refresh();

		// Checks that index does not exist
		$indexNames = $cluster->getIndexNames();
		$this->assertNotContains($index->getName(), $indexNames);

		$index = $this->_createIndex($indexName);
		$cluster->refresh();

		// Now index should exist
		$indexNames = $cluster->getIndexNames();
		$this->assertContains($index->getname(), $indexNames);
	}
}
