<?php
/**
 * Elastica index status object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-status.html
 */
namespace elastica\index;

class Status
{
	protected $_response = null;

	protected $_data = array();

	protected $_name = '';

	/**
	 * @param elastica\Index $index Index object
	 */
	public function __construct(\elastica\Index $index) {
		$this->_index = $index;
		$this->refresh();
	}

	/**
	 * Returns all status info
	 *
	 * @return array Status info
	 */
	public function getData() {
		return $this->_data;
	}

	/**
	 * Returns the entry in the data array based on the params.
	 * Various params possible.
	 *
	 * @return mixed Data array entry or null if not found
	 */
	public function get() {

		$data = $this->getData();
		$data = $data['indices'][$this->getIndex()->getName()];

		foreach (func_get_args() as $arg) {
			if (isset($data[$arg])) {
				$data = $data[$arg];
			} else {
				return null;
			}
		}

		return $data;
	}

	/**
	 * Returns all index aliases
	 *
	 * @return array Aliases
	 */
	public function getAliases() {
		// TODO Update as soon as new API is implmented
		$cluster = new \elastica\Cluster($this->_index->getClient());
		$state = $cluster->getState();
		return $state['metadata']['indices'][$this->_index->getName()]['aliases'];
	}

	/**
	 * Returns all index settings
	 *
	 * @return array Index settings
	 */
	public function getSettings() {
		// TODO Update as soon as new API is implmented
		$cluster = new \elastica\Cluster($this->_index->getClient());
		$state = $cluster->getState();
		return $state['metadata']['indices'][$this->_index->getName()]['settings'];
	}

	/**
	 * Checks if the index has the given alias
	 *
	 * @param string $name Alias name
	 */
	public function hasAlias($name) {
		return in_array($name, $this->getAliases());
	}

	/**
	 * Returns the index object
	 *
	 * @return elastica\Index Index object
	 */
	public function getIndex() {
		return $this->_index;
	}

	/**
	 * Returns response object
	 *
	 * @return elastica\Response Response object
	 */
	public function getResponse() {
		return $this->_response;
	}

	/**
	 * Reloads all status data of this object
	 */
	public function refresh() {
		$path = '_status';
		$this->_response = $this->getIndex()->request($path, \elastica\Request::GET);
		$this->_data = $this->getResponse()->getData();
	}
}
