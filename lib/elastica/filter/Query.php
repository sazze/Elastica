<?php

/**
 * Query filter
 *
 * @uses elastica\filter\Query
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\filter;

class Query extends Abstract_ {

	/**
	 * @param array|elastica\query\Abstract_ $query
	 */
	public function __construct($query = null) {
		if (!is_null($query)) {
			$this->setQuery($query);
		}
	}

	/**
	 * @param array|elastica\query\Abstract_ $query
	 * @return Elastca_Filter_Query Query object
	 * @throws \elastica\exception\Invalid Invalid param
	 */
	public function setQuery($query) {
		if (!$query instanceof \elastica\query\Abstract_ && ! is_array($query)) {
			throw new \elastica\exception\Invalid('expected an array or instance of elastica\query\Abstract_');
		}

		if ($query instanceof \elastica\query\Abstract_) {
			$query = $query->toArray();
		}

		return $this->setParams($query);
	}
}
