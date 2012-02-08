<?php
/**
 * Elastica Abstract_ Transport object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\transport;

abstract class Abstract_ {

	protected $_path;
	// TODO: set default method?
	protected $_method;
	protected $_data;
	protected $_config;


	/**
	 * @param elastica\Request $request Request object
	 */
	public function __construct(\elastica\Request $request) {
		$this->_request = $request;
	}

	/**
	 * Returns the request object
	 *
	 * @return elastica\Request Request object
	 */
	public function getRequest() {
		return $this->_request;
	}

	/**
	 * Executes the transport request
	 *
	 * @param array $params Hostname, port, path, ...
	 * @return elastica\Response Response object
	 */
	abstract public function exec(array $params);
}
