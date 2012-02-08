<?php
/**
 * Bool query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/bool_query/
 */
namespace elastica\query;

class Bool extends Abstract_
{
	/**
	 * Add should part to query
	 *
	 * @param elastica\query\Abstract_|array $args Should query
	 * @return elastica\query\Bool Current object
	 */
	public function addShould($args) {
		return $this->_addQuery('should', $args);
	}

	/**
	 * Add must part to query
	 *
	 * @param elastica\query\Abstract_|array $args Must query
	 * @return elastica\query\Bool Current object
	 */
	public function addMust($args) {
		return $this->_addQuery('must', $args);
	}

	/**
	 * Add must not part to query
	 *
	 * @param elastica\query\Abstract_|array $args Must not query
	 * @return elastica\query\Bool Current object
	 */
	public function addMustNot($args) {
		return $this->_addQuery('must_not', $args);
	}

	/**
	 * Adds a query to the current object
	 *
	 * @param string $type Query type
	 * @param elastica\query\Abstract_|array $args Query
	 * @throws \elastica\exception\Invalid If not valid query
	 */
	protected function _addQuery($type, $args) {
		if ($args instanceof Abstract_) {
			$args = $args->toArray();
		}

		if (!is_array($args)) {
			throw new \elastica\exception\Invalid('Invalid parameter. Has to be array or instance of elastica\query');
		}

		return $this->addParam($type, $args);
	}

	/**
	 * Sets boost value of this query
	 *
	 * @param float $boost Boost value
	 * @return elastica\query\Bool Current object
	 */
	public function setBoost($boost) {
		return $this->setParam('boost', $boost);
	}

	/**
	 * Set the minimum number of of should match
	 *
	 * @param int $minimumNumberShouldMatch Should match minimum
	 * @return elastica\query\Bool Current object
	 */
	public function setMinimumNumberShouldMatch($minimumNumberShouldMatch) {
		return $this->setParam('minimum_number_should_match', $minimumNumberShouldMatch);
	}
}
