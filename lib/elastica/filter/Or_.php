<?php
/**
 * Or Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/or_filter/
 */
namespace elastica\filter;

class Or_ extends Abstract_
{
	protected $_filters = array();

	/**
	 * Adds filter to or filter
	 *
	 * @param elastica\filter\Abstract_ $filter Filter object
	 * @return elastica\filter\Or_ Filter object
	 */
	public function addFilter(Abstract_ $filter) {
		$this->_filters[] = $filter->toArray();
		return $this;
	}

	/**
	 * Convers current object to array.
	 *
	 * @see elastica\filter\Abstract_::toArray()
	 * @return array Or_ array
	 */
	public function toArray() {
		$this->setParams($this->_filters);
		return parent::toArray();
	}
}
