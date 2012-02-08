<?php
/**
 * Elastica index object
 *
 * Handles reads, deletes and configurations of an index
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica;

class Index implements Searchable
{
	/**
	 * @var string Index name
	 */
	protected $_name = '';

	/**
	 * @var elastica\Client Client object
	 */
	protected $_client = null;

	/**
	 * Creates a new index object
	 *
	 * All the communication to and from an index goes of this object
	 *
	 * @param elastica\Client $client Client object
	 * @param string $name Index name
	 */
	public function __construct(Client $client, $name) {
		$this->_client = $client;

		if (!is_string($name)) {
			throw new exception\Invalid('Index name should be of type string');
		}
		$this->_name = $name;
	}

	/**
	 * Returns a type object for the current index with the given name
	 *
	 * @param string $type Type name
	 * @return elastica\Type Type object
	 */
	public function getType($type) {
		return new Type($this, $type);
	}

	/**
	 * Returns the current status of the index
	 *
	 * @return elastica\index\Status Index status
	 */
	public function getStatus() {
		return new index\Status($this);
	}

	/**
	 * @return elastica\index\Stats
	 */
	public function getStats() {
		return new index\Stats($this);
	}

	/**
	 * Returns the index settings object
	 *
	 * @return elastica\index\Settings Settings object
	 */
	public function getSettings() {
		return new index\Settings($this);
	}

	/**
	 * Uses _bulk to send documents to the server
	 *
	 * @param array $docs Array of elastica\Document
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/bulk/
	 */
	public function addDocuments(array $docs) {
		foreach ($docs as $doc) {
			$doc->setIndex($this->getName());
		}

		return $this->getClient()->addDocuments($docs);
	}

	/**
	 * Deletes the index
	 *
	 * @return elastica\Response Response object
	 */
	public function delete() {
		$response = $this->request('', Request::DELETE);

		return $response;
	}

	/**
	 * Optimizes search index
	 *
	 * Detailed arguments can be found here in the link
	 *
	 * @param array $args OPTIONAL Additional arguments
	 * @return array Server response
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/indices/optimize/
	 */
	public function optimize($args = array()) {
		// TODO: doesn't seem to work?
		$this->request('_optimize', Request::POST, $args);
	}

	/**
	 * Refreshs the index
	 *
	 * @return elastica\Response Response object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/indices/refresh/
	 */
	public function refresh() {
		return $this->request('_refresh', Request::POST, array());
	}

	/**
	 * Creates a new index with the given arguments
	 *
	 * @param array $args OPTIONAL Arguments to use
	 * @param bool $recreate OPTIONAL Deletes index first if already exists (default = false)
	 * @return array Server response
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/admin/indices/create_index/
	 */
	public function create(array $args = array(), $recreate = false) {
		if ($recreate) {
			try {
				$this->delete();
			} catch(exception\Response $e) {
				// Table can't be deleted, because doesn't exist
			}
		}
		return $this->request('', Request::PUT, $args);
	}

	/**
	 * Checks if the given index is already created
	 *
	 * @return bool True if index exists
	 */
	public function exists() {
		$cluster = new Cluster($this->getClient());
		return in_array($this->getName(), $cluster->getIndexNames());
	}

	/**
	 * Searchs in this index
	 *
	 * @param string|array|elastica\Query $query Array with all query data inside or a Elastica\Query object
	 * @param int $limit OPTIONAL
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
	 * Counts results of query
	 *
	 * @param string|array|elastica\Query $query Array with all query data inside or a Elastica\Query object
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
	 * Opens an index
	 *
	 * @return elastica\Response Response object
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-open-close.html
	 */
	public function open() {
		$this->request('_open', Request::POST);
	}

	/**
	 * Closes the index
	 *
	 * @return elastica\Response Response object
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-open-close.html
	 */
	public function close() {
		return $this->request('_close', Request::POST);
	}

	/**
	 * Returns the index name
	 *
	 * @return string Index name
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Returns index client
	 *
	 * @return elastica\Client Index client object
	 */
	public function getClient() {
		return $this->_client;
	}


	/**
	 * Adds an alias to the current index
	 *
	 * @param string $name Alias name
	 * @param bool $replace OPTIONAL If set, an existing alias will be replaced
	 * @return elastica\Response Response
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-aliases.html
	 */
	public function addAlias($name, $replace = false) {
		$path = '_aliases';

		if ($replace) {
			$status = new Status($this->getClient());

			foreach ($status->getIndicesWithAlias($name) as $index) {
				$index->removeAlias($name);
			}
		}

		$data = array(
			'actions' => array(
				array(
					'add' => array(
						'index' => $this->getName(),
						'alias' => $name
					)
				)
			)
		);

		return $this->getClient()->request($path, Request::POST, $data);
	}

	/**
	 * Removes an alias pointing to the current index
	 *
	 * @param string $name Alias name
	 * @return elastica\Response Response
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-aliases.html
	 */
	public function removeAlias($name) {
		$path = '_aliases';

		$data = array(
			'actions' => array(
				array(
					'remove' => array(
						'index' => $this->getName(),
						'alias' => $name
					)
				)
			)
		);

		return $this->getClient()->request($path, Request::POST, $data);
	}

	/**
	 * Clears the cache of an index
	 *
	 * @return elastica\Response Reponse object
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-clearcache.html
	 */
	public function clearCache() {
		$path = '_cache/clear';
		// TODO: add additional cache clean arguments
		return $this->request($path, Request::POST);
	}

	/**
	 * Flushs the index to storage
	 *
	 * @return elastica\Response Reponse object
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-flush.html
	 */
	public function flush() {
		$path = '_flush';
		// TODO: Add option for refresh
		return $this->request($path, Request::POST);
	}

	/**
	 * Can be used to change settings during runtime. One example is to use
	 * if for bulk updating {@link http://www.elasticsearch.org/blog/2011/03/23/update-settings.html}
	 *
	 * @param array $data Data array
	 * @return elastica\Response Response object
	 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-update-settings.html
	 */
	public function setSettings(array $data) {
		return $this->request('_settings', Request::PUT, $data);
	}

	/**
	 * Makes calls to the elasticsearch server based on this index
	 *
	 * @param string $path Path to call
	 * @param string $method Rest method to use (GET, POST, DELETE, PUT)
	 * @param array $data OPTIONAL Arguments as array
	 * @return elastica\Response Response object
	 */
	public function request($path, $method, $data = array()) {
		$path = $this->getName() . '/' . $path;
		return $this->getClient()->request($path, $method, $data);
	}
}
