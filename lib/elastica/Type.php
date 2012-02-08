<?php
/**
 * Elastica type object
 *
 * elasticsearch has for every types as a substructure. This object
 * represents a type inside a context
 * The hirarchie is as following: client -> index -> type -> document
 *
 * Search over different indices and types is not supported yet {@link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/indices_types/}
 *
 * @category Xodoa
 * @package  elastica
 * @author   Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica;

class Type implements Searchable {
	/**
	 * @var elastica\Index Index object
	 */
	protected $_index = null;

	/**
	 * @var string Type name
	 */
	protected $_name = '';

	/**
	 * Creates a new type object inside the given index
	 *
	 * @param elastica\Index $index Index Object
	 * @param string         $name  Type name
	 */
	public function __construct(Index $index, $name) {
		$this->_index = $index;
		$this->_name = $name;
	}

	/**
	 * Adds the given document to the search index
	 *
	 * @param elastica\Document $doc Document with data
	 * @return elastica\Response
	 */
	public function addDocument(Document $doc) {

		$path = $doc->getId();

		$query = array();

		if ($doc->getVersion() > 0) {
			$query['version'] = $doc->getVersion();
		}

		if ($doc->getParent()) {
			$query['parent'] = $doc->getParent();
		}

		if ($doc->getOpType()) {
			$query['op_type'] = $doc->getOpType();
		}

		if ($doc->getPercolate()) {
			$query['percolate'] = $doc->getPercolate();
		}

		if (count($query) > 0) {
			$path .= '?' . http_build_query($query);
		}

		$type = Request::PUT;

		// If id is empty, POST has to be used to automatically create id
		if (empty($path)) {
			$type = Request::POST;
		}

		return $this->request($path, $type, $doc->getData());
	}

	/**
	 * Uses _bulk to send documents to the server
	 *
	 * @param elastica\Document[] $docs Array of elastica\Document
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/bulk/
	 */
	public function addDocuments(array $docs) {

		foreach ($docs as $doc) {
			$doc->setType($this->getName());
		}

		return $this->getIndex()->addDocuments($docs);
	}

	/**
	 * Get the document from search index
	 *
	 * @param string $id Document id
	 * @return elastica\Document
	 */
	public function getDocument($id) {
		$path = $id;

		try {
			$result = $this->request($path, Request::GET)->getData();
		} catch (exception\Response $e) {
			throw new exception\NotFound('doc id ' . $id . ' not found');
		}

		if (empty($result['exists'])) {
			throw new exception\NotFound('doc id ' . $id . ' not found');
		}

		$data = isset($result['_source']) ? $result['_source'] : array();
		$document = new Document($id, $data, $this->getName(), $this->getIndex());
		$document->setVersion($result['_version']);
		return $document;
	}

	/**
	 * Returns the type name
	 *
	 * @return string Type
	 * @deprecated Use getName instead
	 */
	public function getType() {
		return $this->getName();
	}

	/**
	 * @return string Type name
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Sets value type mapping for this type
	 *
	 * @param elastica\type\Mapping|array $mapping elastica\type\Mapping object or property array with all mappings
	 * @param bool                        $source  OPTIONAL If source should be stored or not (default = true)
	 */
	public function setMapping($mapping) {

		$mapping = type\Mapping::create($mapping);
		$mapping->setType($this);
		return $mapping->send();
	}

	/**
	 * Returns current mapping for the given type
	 *
	 * @return array Current mapping
	 */
	public function getMapping() {
		$path = '_mapping';

		$response = $this->request($path, Request::GET);
		return $response->getData();
	}

	/**
	 * @param string|array|elastica\Query $query Array with all query data inside or a elastica\Query object
	 * @param int                         $limit OPTIONAL
	 * @return elastica\ResultSet ResultSet with all results inside
	 * @see elastica\Searchable::search
	 */
	public function search($query, $limit = 0) {
		$query = Query::create($query);
		if ($limit) {
			$query->setLimit($limit);
		}
		$path = '_search';

		$response = $this->request($path, Request::GET, $query->toArray());
		return new ResultSet($response);
	}

	/**
	 * @param string|array|elastica\Query $query Array with all query data inside or a elastica\Query object
	 * @return int number of documents matching the query
	 * @see elastica\Searchable::count
	 */
	public function count($query = '') {
		$query = Query::create($query);
		$path = '_count';

		$data = $this->request($path, Request::GET, $query->getQuery())->getData();
		return (int) $data['count'];
	}

	/**
	 * Returns index client
	 *
	 * @return elastica\Index Index object
	 */
	public function getIndex() {
		return $this->_index;
	}

	/**
	 * Deletes an entry by its unique identifier
	 *
	 * @param int|string $id Document id
	 * @return elastica\Response Response object
	 * @link http://www.elasticsearch.org/guide/reference/api/delete.html
	 */
	public function deleteById($id) {
		if (empty($id) || !trim($id)) {
			throw new \InvalidArgumentException();
		}
		return $this->request($id, Request::DELETE);
	}

	/**
	 * Deletes the given list of ids from this type
	 *
	 * @param array $ids
	 * @return elastica\Response Response object
	 */
	public function deleteIds(array $ids) {
		return $this->getIndex()->getClient()->deleteIds($ids, $this->getIndex(), $this);
	}

	/**
	 * Deletes entries in the db based on a query
	 *
	 * @param elastica\Query|string $query Query object
	 * @link http://www.elasticsearch.org/guide/reference/api/delete-by-query.html
	 */
	public function deleteByQuery($query) {
		$query = Query::create($query);
		return $this->request('_query', Request::DELETE, $query->getQuery());
	}

	/**
	 * More like this query based on the given object
	 *
	 * The id in the given object has to be set
	 *
	 * @param EalsticSearch_Document $doc  Document to query for similar objects
	 * @param array                  $args OPTIONAL Additional arguments for the query
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/more_like_this/
	 */
	public function moreLikeThis(Document $doc, $args = array()) {
		// TODO: Not tested yet
		$path = $doc->getId() . '/_mlt';
		return $this->request($path, Request::GET, $args);
	}

	/**
	 * Makes calls to the elasticsearch server based on this type
	 *
	 * @param string $path   Path to call
	 * @param string $method Rest method to use (GET, POST, DELETE, PUT)
	 * @param array  $data   OPTIONAL Arguments as array
	 * @return elastica\Response Response object
	 */
	public function request($path, $method, $data = array()) {
		$path = $this->getName() . '/' . $path;
		return $this->getIndex()->request($path, $method, $data);
	}
}
