<?php

/**
 * Custom score query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Wu Yang <darkyoung@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/custom-score-query.html
 */
namespace elastica\query;

class CustomScore extends Abstract_
{

	/**
	 * Sets query object
	 *
	 * @param string|elastica\Query|elastica\query\Abstract_ $query
	 * @return elastica\query\CustomScore
	 */
	public function setQuery($query) {
		$query = \elastica\Query::create($query);
		$data = $query->toArray();
		return $this->setParam('query', $data['query']);
	}

	/**
	 * @param string $script
	 * @return elastica\query\CustomScore
	 */
	public function setScript($script) {
		return $this->setParam('script', $script);
	}

	/**
	 * Add params
	 *
	 * @param array $params
	 * @return elastica\query\CustomScore
	 */
	public function addParams(array $params) {
		$this->setParam('params', $params);
		return $this;
	}
}
