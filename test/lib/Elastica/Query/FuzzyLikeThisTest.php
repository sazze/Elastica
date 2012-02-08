<?php
require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Query_FuzzyLikeThisTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {
	}

	public function testSearch() {

		$client = new elastica\Client();
		$index = new elastica\Index($client, 'test');
		$index->create(array(), true);
		$index->getSettings()->setNumberOfReplicas(0);
		//$index->getSettings()->setNumberOfShards(1);

		$type = new elastica\Type($index, 'helloworldfuzzy');
        $mapping = new elastica\type\Mapping($type , array(
               'email' => array('store' => 'yes', 'type' => 'string', 'index' => 'analyzed'),
               'content' => array('store' => 'yes', 'type' => 'string',  'index' => 'analyzed'),
          ));

        $mapping->setSource(array('enabled' => false));
        $type->setMapping($mapping);


		$doc = new elastica\Document(1000, array('email' => 'testemail@gmail.com', 'content' => 'This is a sample post. Hello World Fuzzy Like This!'));
		$type->addDocument($doc);

		// Refresh index
		$index->refresh();

		$fltQuery = new elastica\query\FuzzyLikeThis();
        $fltQuery->setLikeText("sample gmail");
        $fltQuery->addFields(array("email","content"));
        $fltQuery->setMinSimilarity(0.3);
        $fltQuery->setMaxQueryTerms(3);
		$resultSet = $type->search($fltQuery);
		$this->assertEquals(1, $resultSet->count());
	}

	public function testSetPrefixLength() {
		$query = new elastica\query\FuzzyLikeThis();

		$length = 3;
		$query->setPrefixLength($length);

		$data = $query->toArray();

		$this->assertEquals($length, $data['fuzzy_like_this']['prefix_length']);
	}

	public function testAddFields() {
		$query = new elastica\query\FuzzyLikeThis();

		$fields = array('test1', 'test2');
		$query->addFields($fields);

		$data = $query->toArray();

		$this->assertEquals($fields, $data['fuzzy_like_this']['fields']);
	}

	public function testSetLikeText() {
		$query = new elastica\query\FuzzyLikeThis();

		$text = ' hello world';
		$query->setLikeText($text);

		$data = $query->toArray();

		$this->assertEquals(trim($text), $data['fuzzy_like_this']['like_text']);
	}

	public function testSetMinSimilarity() {
		$query = new elastica\query\FuzzyLikeThis();

		$similarity = 2;
		$query->setMinSimilarity($similarity);

		$data = $query->toArray();

		$this->assertEquals($similarity, $data['fuzzy_like_this']['min_similarity']);
	}


	public function testSetBoost() {
		$query = new elastica\query\FuzzyLikeThis();

		$boost = 2.2;
		$query->setBoost($boost);

		$data = $query->toArray();

		$this->assertEquals($boost, $data['fuzzy_like_this']['boost']);
	}
}
