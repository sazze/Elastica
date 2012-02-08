<?php
/**
 * Percolator class
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/api/percolate.html
 */
namespace elastica;

class Percolator
{
	/**
	 * @var elastica\Index
	 */
	protected $_index = null;

	/**
	 * @param elastica\Index $index
	 */
	public function __construct(Index $index) {
		$this->_index = $index;
	}

	/**
	 * Registers a percolator query
	 *
	 * @param string $name Query name
	 * @param string|elastica\query|elastica\query\Abstract $query Query to add
	 * @return elastica\Resonse
	 */
	public function registerQuery($name, $query) {
		$path = '_percolator/' . $this->_index->getName() . '/' . $name;
		$query = Query::create($query);
		return $this->_index->getClient()->request($path, Request::PUT, $query->toArray());
	}

	/**
	 * Match a document to percolator queries
	 *
	 * @param elastica\Document $doc
	 * @param string|elastica\query|elastica\query\Abstract $query Not implemented yet
	 * @return elastica\Resonse
	 */
	public function matchDoc(Document $doc, $query = null) {
		$path = $this->_index->getName() . '/type/_percolate';
		$data = array('doc' => $doc->getData());

		$response = $this->getIndex()->getClient()->request($path, Request::GET, $data);
		$data = $response->getData();

		return $data['matches'];
	}

	/**
	 * @return elastica\Index
	 */
	public function getIndex() {
		return $this->_index;
	}
}
