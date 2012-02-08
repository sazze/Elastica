<?php
/**
 * Custom filtered score query. Needs a query and array of filters, with boosts
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author James Wilson <jwilson556@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/custom-filters-score-query.html
 */
namespace elastica\query;

class CustomFiltersScore extends Abstract_
{
	/**
	 * Sets a query
	 *
	 * @param elastica\query\Abstract_ $query Query object
	 * @return elastica\query\CustomFiltersScore Current object
	 */
	public function setQuery(Abstract_ $query) {
		$this->setParam('query', $query->toArray());
		return $this;
	}

	/**
	 * Add a filter with boost
	 *
	 * @param elastica\filter\Abstract_ $filter Filter object
	 * @param float $boost Boost for the filter
	 * @return elastica\query\CustomFiltersScore Current object
	 */
	public function addFilter(elastica\filter\Abstract_ $filter, $boost) {
		$filter_param = array('filter' => $filter->toArray(), 'boost' => $boost);
		$this->addParam('filters', $filter_param);
		return $this;
	}
}
