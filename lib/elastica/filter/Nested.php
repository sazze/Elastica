<?php
/**
 * Nested filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/nested-filter.html
 */
namespace elastica\filter;

class Nested extends Abstract_
{
	/**
	 * Adds field to mlt filter
	 *
	 * @param string $path Nested object path
	 * @return elastica\filter\Nested
	 */
	public function setPath($path) {
		return $this->setParam('path', $path);
	}

	/**
	 * Sets nested query
	 *
	 * @param elastica\query\Abstract_ $filter
	 * @return elastica\filter\Nested
	 */
	public function setQuery(\elastica\query\Abstract_ $query) {
		return $this->setParam('query', $query->toArray());
	}

	/**
	 * @param string $scoreMode Options: avg, total, max and none.
	 * @return elastica\filter\Nested
	 */
	public function setScoreMode($scoreMode) {
		return $this->setParam('score_mode', $scoreMode);
	}
}
