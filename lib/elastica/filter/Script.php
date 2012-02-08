<?php

/**
 * Script filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/script-filter.html
 */
namespace elastica\filter;

class Script extends Abstract_ {

	/**
	 * @var array|elastica\query\Abstract_
	 */
	protected $_query = null;

	/**
	 * @param array|elastica\query\Abstract_ $query OPTIONAL Query object
	 */
	public function __construct($query = null) {
		if (!is_null($query)) {
			$this->setQuery($query);
		}
	}

	/**
	 * Sets query object
	 *
	 * @param array|elastica\query\Abstract_ $query
	 * @return elastica\filter\Script
	 * @throws \elastica\exception\Invalid Invalid argument type
	 */
	public function setQuery($query) {
		// TODO: check if should be renamed to setScript?
		if (!$query instanceof elastica\query\Abstract_ && !is_array($query)) {
			throw new \elastica\exception\Invalid('expected an array or instance of elastica\query\Abstract_');
		}

		if ($query instanceof elastica\query\Abstract_) {
			$this->_query = $query->toArray();
		} else {
			$this->_query = $query;
		}

		return $this;
	}

	/**
	 * @return array Script filter
	 * @see elastica\filter\Abstract_::toArray()
	 */
	public function toArray() {
		return array(
			'script' => (
				$this->_query
			),
		);
	}
}
