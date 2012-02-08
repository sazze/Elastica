<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';

class Elastica_NodeTest extends Elastica_Test
{
	public function setUp() { }

	public function tearDown() { }

	public function testCreateNode() {

		$client = new elastica\Client();
		$names = $client->getCluster()->getNodeNames();
		$name = reset($names);

		$node = new elastica\Node($name, $client);
		$this->assertInstanceOf('elastica\Node', $node);
	}

	public function testGetInfo() {
		$client = new elastica\Client();
		$names = $client->getCluster()->getNodeNames();
		$name = reset($names);

		$node = new elastica\Node($name, $client);

		$info = $node->getInfo();

		$this->assertInstanceOf('elastica\node\Info', $info);
	}

	public function testGetStats() {
		$client = new elastica\Client();
		$names = $client->getCluster()->getNodeNames();
		$name = reset($names);

		$node = new elastica\Node($name, $client);

		$stats = $node->getStats();

		$this->assertInstanceOf('elastica\node\Stats', $stats);
	}

	public function testShutdown() {
		$client = new elastica\Client();
		$nodes = $client->getCluster()->getNodes();

		$count = count($nodes);
		if ($count < 2) {
			$this->markTestSkipped('At least two nodes have to be running, because 1 node is shutdown');
		}

		// Stores node info for later
		$info = $nodes[1]->getInfo();
		$nodes[0]->shutdown('2s');

		sleep(5);

		$client = new elastica\Client(array('host' => $info->getIp(), 'port' => $info->getPort()));
		$names = $client->getCluster()->getNodeNames();

		// One node less ...
		$this->assertEquals($count - 1, count($names));
	}
}
