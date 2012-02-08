<?php
/**
 * Elastica index stats object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/api/admin-indices-stats.html
 */
namespace elastica\index;

class Stats
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
	 * Returns the raw stats info
	 *
	 * @return array Stats info
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
		$path = '_stats';
		$this->_response = $this->getIndex()->request($path, \elastica\Request::GET);
		$this->_data = $this->getResponse()->getData();
	}
}
