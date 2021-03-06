<?php
/**
 * Term query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/term_query/
 */
namespace elastica\filter;

class Term extends Abstract_
{
	/**
	 * @param array $term Term array
	 */
	public function __construct(array $term = array()) {
		$this->setRawTerm($term);
	}

	/**
	 * Sets/overwrites key and term directly
	 *
	 * @param array $term Key value pair
	 * @return elastica\filter\Term Filter object
	 */
	public function setRawTerm(array $term) {
		return $this->setParams($term);
	}

	/**
	 * Adds a term to the term query
	 *
	 * @param string $key Key to query
	 * @param string|array $value Values(s) for the query. Boost can be set with array
	 * @return elastica\filter\Term Filter object
	 */
	public function setTerm($key, $value) {
		return $this->setRawTerm(array($key => $value));
	}
}
