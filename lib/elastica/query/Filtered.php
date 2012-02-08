<?php
/**
 * Filtered query. Needs a query and a filter
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/fuzzy_query/
 */
namespace elastica\query;

class Filtered extends Abstract_
{
	protected $_query = null;
	protected $_filter = null;

	/**
	 * Constructs a filtered query
	 *
	 * @param elastica\query\Abstract_ $query Query object
	 * @param elastica\filter\Abstract_ $filter Filter object
	 */
	public function __construct(Abstract_ $query, \elastica\filter\Abstract_ $filter) {
		$this->setQuery($query);
		$this->setFilter($filter);
	}

	/**
	 * Sets a query
	 *
	 * @param elastica\query\Abstract_ $query Query object
	 * @return elastica\query\Filtered Current object
	 */
	public function setQuery(Abstract_ $query) {
		$this->_query = $query;
		return $this;
	}

	/**
	 * Sets the filter
	 *
	 * @param elastica\filter\Abstract_ $filter Filter object
	 * @return elastica\query\Filtered Current object
	 */
	public function setFilter(\elastica\filter\Abstract_ $filter) {
		$this->_filter = $filter;
		return $this;
	}

	/**
	 * Converts query to array
	 *
	 * @return array Query array
	 * @see elastica\query\Abstract_::toArray()
	 */
	public function toArray() {
		return array('filtered' => array(
			'query' => $this->_query->toArray(),
			'filter' => $this->_filter->toArray()
		));
	}
}
