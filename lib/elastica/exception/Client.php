<?php
/**
 * Client exception
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\exception;

class Client extends Abstract_ {

	protected $_error = 0;
	protected $_request = null;
	protected $_response = null;

	/**
	 * @param string $error Error
	 * @param elastica\transport\Abstract_ $request
	 * @param elastica\Response $response
	 */
	public function __construct($error, \elastica\Request $request = null, \elastica\Response $response = null) {
		$this->_error = $error;
		$this->_request = $request;
		$this->_response = $response;

		$message = $this->getErrorMessage($this->getError());
		parent::__construct($message);
	}

	/**
	 * Returns the error message corresponding to the error code
	 * cUrl error code reference can be found here {@link http://curl.haxx.se/libcurl/c/libcurl-errors.html}
	 *
	 * @param string $error Error code
	 * @return string Error message
	 */
	public function getErrorMessage($error) {

		switch ($error) {
			case CURLE_UNSUPPORTED_PROTOCOL:
				$error = "Unsupported protocol";
				break;
			case CURLE_FAILED_INIT:
				$error = "Internal cUrl error?";
				break;
			case CURLE_URL_MALFORMAT:
				$error = "Malformed URL";
				break;
			case CURLE_COULDNT_RESOLVE_PROXY:
				$error = "Couldnt resolve proxy";
				break;
			case CURLE_COULDNT_RESOLVE_HOST:
				$error = "Couldnt resolve host";
				break;
			case CURLE_COULDNT_CONNECT:
				$error = "Couldnt connect to host, ElasticSearch down?";
				break;
			case 28:
				$error = "Operation timed out";
				break;
			default:
				$error = "Unknown error:" . $error;
				break;
		}

		return $error;
	}

	/**
	 * @return string Error code / message
	 */
	public function getError() {
		return $this->_error;
	}

	/**
	 * Returns request object
	 *
	 * @return elastica\transport\Abstract_ Request object
	 */
	public function getRequest() {
		return $this->_request;
	}

	/**
	 * Returns response object
	 *
	 * @return elastica\Response Response object
	 */
	public function getResponse() {
		return $this->_response;
	}
}
