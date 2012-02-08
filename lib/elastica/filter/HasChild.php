<?php

/**
 * Returns parent documents having child docs matching the query
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Fabian Vogler <fabian@equivalence.ch>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/has-child-filter.html
 */
namespace elastica\filter;

class HasChild extends Abstract_
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
	 * @return elastica\filter\HasChild Current object
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
	 * @return elastica\filter\HasChild Current object
	 */
	public function setType($type) {
		return $this->setParam('type', $type);
	}
}
