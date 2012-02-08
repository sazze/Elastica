<?php

/**
 * Runs the child query with an estimated hits size, and out of the hit docs, aggregates it into parent docs.
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Wu Yang <darkyoung@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/top-children-query.html
 */
namespace elastica\query;

class TopChildren extends Abstract_
{
	/**
	 * @param string|elastica\Query $query Query string or a elastica\Query object
	 * @param string $type Parent document type
	 */
	public function __construct($query, $type = null) {
		$this->setQuery($query);
		$this->setType($type);
	}

	/**
	 * Sets query object
	 *
	 * @param string|elastica\Query|elastica\query\Abstract_ $query
	 * @return elastica\query\TopChildren
	 */
	public function setQuery($query) {
		$query = elastica\Query::create($query);
		$data = $query->toArray();
		return $this->setParam('query', $data['query']);
	}

	/**
	 * Set type of the parent document
	 *
	 * @param string $type Parent document type
	 * @return elastica\query\TopChildren Current object
	 */
	public function setType($type) {
		return $this->setParam('type', $type);
	}
}
