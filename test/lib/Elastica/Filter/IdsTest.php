<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Filter_IdsTest extends PHPUnit_Framework_TestCase
{
	protected $_index;
	protected $_type;

	public function setUp() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->create(array(), true);

		$type1 = $index->getType('helloworld1');
		$type2 = $index->getType('helloworld2');

		// Add documents to first type
		for ($i = 1; $i < 100; $i++) {
			$doc = new elastica\Document($i, array('name' => 'ruflin'));
			$type1->addDocument($doc);
		}

		// Add documents to second type
		for ($i = 1; $i < 100; $i++) {
			$doc = new elastica\Document($i, array('name' => 'ruflin'));
			$type2->addDocument($doc);
		}

		// This is a special id that will only be in the second type
		$doc = new elastica\Document('101', array('name' => 'ruflin'));
		$type2->addDocument($doc);

		$index->optimize();
		$index->refresh();

		$this->_type = $type1;
		$this->_index = $index;
	}

	public function tearDown() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->delete();
	}

	public function testSetIdsSearchSingle() {
		$filter = new elastica\filter\Ids();
		$filter->setIds('1');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		$this->assertEquals(1, $resultSet->count());
	}

	public function testSetIdsSearchArray() {
		$filter = new elastica\filter\Ids();
		$filter->setIds(array(1, 7, 13));

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		$this->assertEquals(3, $resultSet->count());
	}

	public function testAddIdsSearchSingle() {
		$filter = new elastica\filter\Ids();
		$filter->addId('39');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		$this->assertEquals(1, $resultSet->count());
	}

	public function testAddIdsSearchSingleNotInType() {
		$filter = new elastica\filter\Ids();
		$filter->addId('39');

		// Add an ID that is not in the index
		$filter->addId(104);

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		$this->assertEquals(1, $resultSet->count());
	}

	public function testComboIdsSearchArray() {
		$filter = new elastica\filter\Ids();
		$filter->setIds(array(1, 7, 13));
		$filter->addId('39');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		$this->assertEquals(4, $resultSet->count());
	}

	public function testSetTypeSingleSearchSingle() {
		$filter = new elastica\filter\Ids();
		$filter->setIds('1');
		$filter->setType('helloworld1');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_index->search($query);

		$this->assertEquals(1, $resultSet->count());
	}

	public function testSetTypeSingleSearchArray() {
		$filter = new elastica\filter\Ids();
		$filter->setIds(array('1', '2'));
		$filter->setType('helloworld1');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_index->search($query);

		$this->assertEquals(2, $resultSet->count());
	}

	public function testSetTypeSingleSearchSingleDocInOtherType() {
		$filter = new elastica\filter\Ids();

		// Doc 4 is in the second type...
		$filter->setIds('101');
		$filter->setType('helloworld1');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		// ...therefore 0 results should be returned
		$this->assertEquals(0, $resultSet->count());
	}

	public function testSetTypeSingleSearchArrayDocInOtherType() {
		$filter = new elastica\filter\Ids();

		// Doc 4 is in the second type...
		$filter->setIds(array('1', '101'));
		$filter->setType('helloworld1');

		$query = elastica\Query::create($filter);
		$resultSet = $this->_type->search($query);

		// ...therefore only 1 result should be returned
		$this->assertEquals(1, $resultSet->count());
	}

	public function testSetTypeArraySearchArray() {
		$filter = new elastica\filter\Ids();
		$filter->setIds(array('1', '4'));
		$filter->setType(array('helloworld1', 'helloworld2'));

		$query = elastica\Query::create($filter);
		$resultSet = $this->_index->search($query);

		$this->assertEquals(4, $resultSet->count());
	}

	public function testSetTypeArraySearchSingle() {
		$filter = new elastica\filter\Ids();
		$filter->setIds('4');
		$filter->setType(array('helloworld1', 'helloworld2'));

		$query = elastica\Query::create($filter);
		$resultSet = $this->_index->search($query);

		$this->assertEquals(2, $resultSet->count());
	}
}

?>
