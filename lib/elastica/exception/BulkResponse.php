<?php
/**
 * Bulk Response exception
 *
 * @category Xodoa
 * @package elastica
 */
namespace elastica\exception;

class BulkResponse extends Abstract_ {

	protected $_response = null;

	/**
	 * @param elastica\Response $response
	 */
	public function __construct(elastica\Response $response) {
		$this->_response = $response;
		parent::__construct('Error in one or more bulk request actions');
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
	 * Returns array of failed actions
	 *
	 * @return array Array of failed actions
	 */
	public function getFailures() {
		$data = $this->_response->getData();
		$errors = array();

		foreach($data['items'] as $item) {
			$meta = reset($item);
			$action = key($item);
			if(isset($meta['error'])) {
				$error = array(
					'action' => $action,
				);
				foreach($meta as $key => $value) {
					$key = ltrim($key, '_');
					$error[$key] = $value;
				}

				$errors[] = $error;
			}
		}

		return $errors;
	}
}
