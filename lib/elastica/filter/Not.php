<?php
/**
 * Not Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Lee Parker, Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/not-filter.html
 */
namespace elastica\filter;

class Not extends Abstract_
{
	/**
	 * Creates Not filter query
	 *
	 * @param elastica\filter\Abstract_ $filter Filter object
	 */
	public function __construct(Abstract_ $filter) {
		$this->setFilter($filter);
	}

	/**
	 * @param elastica\filter\Abstract_ $filter
	 * @return elastica\filter\Not
	 */
	public function setFilter(Abstract_ $filter) {
		return $this->setParam('filter', $filter->toArray());
	}
}
