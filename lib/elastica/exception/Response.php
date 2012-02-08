<?php
/**
 * Response exception
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\exception;

class Response extends Abstract_ {

	protected $_response = null;

	/**
	 * @param elastica\Response $response
	 */
	public function __construct(\elastica\Response $response) {
		$this->_response = $response;
		parent::__construct($response->getError());
	}

	/**
	 * Returns reponsce object
	 *
	 * @return elastica\Response Response object
	 */
	public function getResponse() {
		return $this->_response;
	}
}
