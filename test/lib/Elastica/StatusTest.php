<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';

class Elastica_StatusTest extends Elastica_Test
{
	public function setUp() {

	}

	public function tearDown() {
	}

	public function testGetResponse() {
		$index = $this->_createIndex();
		$status = new elastica\Status($index->getClient());
		$this->assertInstanceOf('elastica\Response', $status->getResponse());
	}

	public function testGetIndexStatuses() {
		$index = $this->_createIndex();

		$status = new elastica\Status($index->getClient());
		$statuses = $status->getIndexStatuses();

		$this->assertInternalType('array', $statuses);

		foreach($statuses as $indexStatus) {
			$this->assertInstanceOf('elastica\index\Status', $indexStatus);
		}
	}

	public function testGetIndexNames() {
		$indexName = 'test';
		$client = new elastica\Client();
		$index = $client->getIndex($indexName);
		$index->create(array(), true);
		$index = $this->_createIndex();

		$status = new elastica\Status($index->getClient());
		$names = $status->getIndexNames();

		$this->assertInternalType('array', $names);
		$this->assertTrue(in_array($index->getName(), $names));

		foreach($names as $name) {
			$this->assertInternalType('string', $name);
		}
	}

	public function testIndexExists() {
		$indexName = 'elastica_test';
		$aliasName = 'elastica_test-alias';

		$client = new elastica\Client();
		$index = $client->getIndex($indexName);

		try {
			// Make sure index is deleted first
			$index->delete();
		} catch(elastica\exception\Response $e) { }

		$status = new elastica\Status($client);
		$this->assertFalse($status->indexExists($indexName));
		$index->create();

		$status->refresh();
		$this->assertTrue($status->indexExists($indexName));
	}

	public function testAliasExists() {
		$indexName = 'test';
		$aliasName = 'elastica_test-alias';

		$index1 = $this->_createIndex();

		$status = new elastica\Status($index1->getClient());

		foreach($status->getIndicesWithAlias($aliasName) as $tmpIndex) {
			$tmpIndex->removeAlias($aliasName);
		}

		$this->assertFalse($status->aliasExists($aliasName));

		$index1->addAlias($aliasName);
		$status->refresh();
		$this->assertTrue($status->aliasExists($aliasName));
	}

    public function testServerStatus() {

        $client = new elastica\Client();
        $status = $client->getStatus();
        $serverStatus = $status->getServerStatus();

        $this->assertTrue(!empty($serverStatus) );
        $this->assertTrue('array' == gettype($serverStatus));
        $this->assertArrayHasKey('ok', $serverStatus);
        $this->assertTrue($serverStatus['ok']);
        $this->assertArrayHasKey('version', $serverStatus);

        $versionInfo = $serverStatus['version'];
        $this->assertArrayHasKey('number', $versionInfo);
    }


}
