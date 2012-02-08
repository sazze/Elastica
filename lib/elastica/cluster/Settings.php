<?php
/**
 * Cluster settings
 *
 * @category Xodoa
 * @package  elastica
 * @author   Nicolas Ruflin <spam@ruflin.com>
 * @link     http://www.elasticsearch.org/guide/reference/api/admin-cluster-update-settings.html
 */
namespace elastica\cluster;

class Settings {
	protected $_client = null;

	/**
	 * Creates a cluster object
	 *
	 * @param elastica\Client $client Connection client object
	 */
	public function __construct(\elastica\Client $client) {
		$this->_client = $client;
	}

	/**
	 * @return array Settings data (persistent and transient)
	 */
	public function get() {
		return $this->request()->getData();
	}

	/**
	 * Returns the current persistent settings of the cluster
	 *
	 * If param is set, only specified setting is return.
	 *
	 * @param string $setting OPTIONAL Setting name to return
	 * @return array|string|null Settings data
	 */
	public function getPersistent($setting = '') {

		$data = $this->get();
		$settings = $data['persistent'];

		if (!empty($setting)) {
			if (isset($settings[ $setting])) {
				return $settings[$setting];
			} else {
				return null;
			}
		}
		return $settings;
	}

	/**
	 * Returns the current transient settings of the cluster
	 *
	 * If param is set, only specified setting is return.
	 *
	 * @param string $setting OPTIONAL Setting name to return
	 * @return array|string|null Settings data
	 */
	public function getTransient($setting = '') {

		$data = $this->get();
		$settings = $data['transient'];

		if (!empty($setting)) {
			if (isset($settings[ $setting])) {
				return $settings[$setting];
			} else {
				return null;
			}
		}
		return $settings;
	}

	/**
	 * Sets persistent setting
	 *
	 * @param string $key
	 * @param string $value
	 * @return elastica\Response
	 */
	public function setPersistent($key, $value) {
		return $this->set(
			array(
				'persistent' => array(
					$key => $value
				)
			)
		);
	}

	/**
	 * Sets transient settings
	 *
	 * @param string $key
	 * @param string $value
	 * @return elastica\Response
	 */
	public function setTransient($key, $value) {
		return $this->set(
			array(
				'transient' => array(
					$key => $value
				)
			)
		);
	}

	/**
	 * Sets the cluster to read only
	 *
	 * Second param can be used to set it persistent
	 *
	 * @param bool $readOnly
	 * @param bool $persistent
	 * @return elastica\Response $response
	 */
	public function setReadOnly($readOnly = true, $persistent = false) {
		$key = 'cluster.blocks.read_only';

		if ($persistent) {
			$response = $this->setPersistent($key, $readOnly);
		} else {
			$response = $this->setTransient($key, $readOnly);
		}
		return $response;
	}

	/**
	 * @param array $settings Raw settings (including persistent or transient)
	 * @return elastica\Response
	 */
	public function set(array $settings) {
		return $this->request($settings, \elastica\Request::PUT);
	}

	/**
	 * @return elastica\Client
	 */
	public function getClient() {
		return $this->_client;
	}

	/**
	 * Sends settings request
	 *
	 * @param array  $data   OPTIONAL Data array
	 * @param string $method OPTIONAL Transfer method (default = elastica\Request::GET)
	 * @return elastica\Response Response object
	 */
	public function request(array $data = array(), $method = \elastica\Request::GET) {
		$path = '_cluster/settings';
		return $this->getClient()->request($path, $method, $data);
	}
}
