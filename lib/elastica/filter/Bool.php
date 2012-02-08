<?php
/**
 * Bool Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/bool_query/
 */
namespace elastica\filter;

class Bool extends Abstract_
{
	protected $_minimumNumberShouldMatch = 1;

	protected $_must = array();
	protected $_should = array();
	protected $_mustNot = array();

	/**
	 * Adds should filter
	 *
	 * @param array|elastica\filter\Abstract_ $args Filter data
	 * @return elastica\filter\Bool Current object
	 */
	public function addShould($args) {
		return $this->_addFilter('should', $args);
	}

	/**
	 * Adds must filter
	 *
	 * @param array|elastica\filter\Abstract_ $args Filter data
	 * @return elastica\filter\Bool Current object
	 */
	public function addMust($args) {
		return $this->_addFilter('must', $args);
	}

	/**
	 * Adds mustNot filter
	 *
	 * @param array|elastica\filter\Abstract_ $args Filter data
	 * @return elastica\filter\Bool Current object
	 */
	public function addMustNot($args) {
		return $this->_addFilter('mustNot', $args);
	}

	/**
	 * Adds general filter based on type
	 *
	 * @param string $type Filter type
	 * @param array|elastica\filter\Abstract_ $args Filter data
	 * @return elastica\filter\Bool Current object
	 */
	protected function _addFilter($type, $args) {
		if ($args instanceof Abstract_) {
			$args = $args->toArray();
		}

		if (!is_array($args)) {
			throw new \elastica\exception\Invalid('Invalid parameter. Has to be array or instance of elastica\filter');
		}

		$varName = '_' . $type;
		$this->{$varName}[] = $args;
		return $this;
	}

	/**
	 * Converts bool filter to array
	 *
	 * @see elastica\filter\Abstract_::toArray()
	 * @return array Filter array
	 */
	public function toArray() {
		$args = array();

		if (!empty($this->_must)) {
			$args['must'] = $this->_must;
		}

		if (!empty($this->_should)) {
			$args['should'] = $this->_should;
			$args['minimum_number_should_match'] = $this->_minimumNumberShouldMatch;
		}

		if (!empty($this->_mustNot)) {
			$args['must_not'] = $this->_mustNot;
		}

		return array('bool' => $args);
	}

	/**
	 * Sets the boost value for this filter
	 *
	 * @param float $boost Boost
	 * @return elastica\filter\Bool Current object
	 */
	public function setBoost($boost) {
		$this->_boost = $boost;
		return $this;
	}

	/**
	 * Sets the minimum number that should filter have to match
	 *
	 * @param int $minimumNumberShouldMatch Number of matches
	 * @return elastica\filter\Bool Current object
	 */
	public function setMinimumNumberShouldMatch($minimumNumberShouldMatch) {
		$this->_minimumNumberShouldMatch = intval($minimumNumberShouldMatch);
		return $this;
	}

}
