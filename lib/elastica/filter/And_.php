<?php
/**
 * And Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Lee Parker, Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/and-filter.html
 */
namespace elastica\filter;

class And_ extends Abstract_
{
	/**
	 * Adds one more filter to the and filter
	 *
	 * @param elastica\filter\Abstract_ $filter
	 * @return elastica\filter\And_ Current object
	 */
	public function addFilter(Abstract_ $filter) {
		$this->_params[] = $filter->toArray();
		return $this;
	}
}
