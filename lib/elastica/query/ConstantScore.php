<?php

/**
 * Constant score query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/constant-score-query.html
 */
namespace elastica\query;

class ConstantScore extends Abstract_
{

	/**
	 * @param null|elastica\filter\Abstract_|array $filter
	 */
	public function __construct($filter = null) {
		if(!is_null($filter)) {
			$this->setFilter($filter);
		}
	}

	/**
	 * @param array|elastica\filter\Abstract_ $filter
	 * @return elastica\query\ConstantScore Query object
	 */
	public function setFilter($filter) {
		if ($filter instanceof elastica\filter\Abstract_) {
			$filter = $filter->toArray();
		}
		return $this->setParam('filter', $filter);
	}

	/**
	 * @param float $boost
	 * @return elastica\query\ConstantScore
	 */
	public function setBoost($boost) {
		return $this->setParam('boost', $boost);
	}
}
