<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_HighlightTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testHightlightSearch() {
		$client = new elastica\Client();
		$index = $client->getIndex('test');
		$index->create(array(), true);
		$type = $index->getType('helloworld');

		$phrase = 'My name is ruflin';

		$doc = new elastica\Document(1, array('id' => 1, 'phrase' => $phrase, 'username' => 'hanswurst', 'test' => array('2', '3', '5')));
		$type->addDocument($doc);
		$doc = new elastica\Document(2, array('id' => 2, 'phrase' => $phrase, 'username' => 'peter', 'test' => array('2', '3', '5')));
		$type->addDocument($doc);

		$queryString = new elastica\query\QueryString('rufl*');
		$query = new elastica\Query($queryString);
		$query->setHighlight(array(
			'pre_tags' => array('<em class="highlight">'),
			'post_tags' => array('</em>'),
			'fields' => array(
				'phrase' => array(
					'fragment_size' => 200,
					'number_of_fragments' => 1,
				),
			),
		));



		$index->refresh();

		$resultSet = $type->search($query);
		foreach ($resultSet as $result) {
			$highlight = $result->getHighlights();
			$this->assertEquals(array('phrase' => array(0 => 'My name is <em class="highlight">ruflin</em>')), $highlight);
		}
		$this->assertEquals(2, $resultSet->count());

	}
}
