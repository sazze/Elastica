<?php
/**
 * Elastica Http Transport object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\transport;

class Https extends Http {

	/**
	 * @var string https scheme
	 */
	protected $_scheme = 'https';

	/**
	 * Overloads setupCurl to set SSL params
	 *
	 * @param resource $connection Curl connection resource
	 */
	protected function _setupCurl($connection) {
		parent::_setupCurl($connection);
	}
}
