<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_MappingTest extends Elastica_Test
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testMappingStoreFields() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');

		$index->create(array(), true);
		$type = $index->getType('test');

		$mapping = new elastica\type\Mapping($type,
			array(
				'firstname' => array('type' => 'string', 'store' => 'yes'),
				// default is store => no expected
				'lastname' => array('type' => 'string'),
			)
		);
		$mapping->disableSource();

		$type->setMapping($mapping);

		$firstname = 'Nicolas';
		$doc = new elastica\Document(1,
			array(
				'firstname' => $firstname,
				'lastname' => 'Ruflin'
			)
		);

		$type->addDocument($doc);

		$index->refresh();
		$queryString = new elastica\query\QueryString('ruflin');
		$query = elastica\Query::create($queryString);
		$query->setFields(array('*'));

		$resultSet = $type->search($query);
		$result = $resultSet->current();
		$fields = $result->getFields();

		$this->assertEquals($firstname, $fields['firstname']);
		$this->assertArrayNotHasKey('lastname', $fields);
		$this->assertEquals(1, count($fields));

		$index->flush();
		$document = $type->getDocument(1);

		$this->assertEmpty($document->getData());
	}

	public function testEnableTTL() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');

		$index->create(array(), true);
		$type = $index->getType('test');

		$mapping = new elastica\type\Mapping($type, array());

		$mapping->enableTTL();

		$data = $mapping->toArray();
		$this->assertTrue($data[$type->getName()]['_ttl']['enabled']);
	}

	public function testNestedMapping() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');

		$index->create(array(), true);
		$type = $index->getType('test');

		$this->markTestIncomplete('nested mapping is not set right yet');
		$mapping = new elastica\type\Mapping($type,
			array(
				'test' => array(
					'type' => 'object', 'store' => 'yes', 'properties' => array(
						'user' => array(
							'properties' => array(
								'firstname' => array('type' => 'string', 'store' => 'yes'),
								'lastname' => array('type' => 'string', 'store' => 'yes'),
								'age' => array('type' => 'integer', 'store' => 'yes'),
							)
						),
					),
				),
			)
		);

		$type->setMapping($mapping);

		$doc = new elastica\Document(1, array(
			'user' => array(
				'firstname' => 'Nicolas',
				'lastname' => 'Ruflin',
				'age' => 9
			),
		));

		print_r($type->getMapping());
		exit();
		$type->addDocument($doc);

		$index->refresh();
		$resultSet = $type->search('ruflin');
		print_r($resultSet);
	}

	public function testParentMapping() {
		$index = $this->_createIndex();
		$parenttype = new elastica\Type($index, 'parenttype');
		$parentmapping = new elastica\type\Mapping($parenttype,
			array(
				'name' => array('type' => 'string', 'store' => 'yes')
			)
		);

		$parenttype->setMapping($parentmapping);


		$childtype = new elastica\Type($index, 'childtype');
		$childmapping = new elastica\type\Mapping($childtype,
			array(
				'name' => array('type' => 'string', 'store' => 'yes'),
			)
		);
		$childmapping->setParam('_parent', array('type' => 'parenttype'));

		$childtype->setMapping($childmapping);
	}

	public function testMappingExample() {

		$index = $this->_createIndex();
		$type = $index->getType('notes');

		$mapping = new elastica\type\Mapping($type,
			array(
				'note' => array(
					'store' => 'yes', 'properties' => array(
						'titulo'  => array('type' => 'string', 'store' => 'no', 'include_in_all' => TRUE, 'boost' => 1.0),
						'contenido' => array('type' => 'string', 'store' => 'no', 'include_in_all' => TRUE, 'boost' => 1.0)
					)
				)
			)
		);

		$type->setMapping($mapping);

		$doc = new elastica\Document(1, array(
				'note' => array(
					array(
						'titulo'        => 'nota1',
						'contenido'        => 'contenido1'
					),
					array(
						'titulo'        => 'nota2',
						'contenido'        => 'contenido2'
					)
				)
			)
		);

		$type->addDocument($doc);
	}
}
