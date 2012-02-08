<?php
/**
 * Match all query. Returns all results
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\query;

class MatchAll extends Abstract_
{
	/**
	 * Creates match all query
	 */
	public function __construct() {
		$this->_params = new \stdClass();
	}
}
