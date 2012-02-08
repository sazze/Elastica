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
namespace elastica\query;

class Term extends Abstract_
{
	/**
	 * Constructs the Term query object
	 *
	 * @param array $term OPTIONAL Calls setTerm with the given $term array
	 */
	public function __construct(array $term = array()) {
		$this->setRawTerm($term);
	}

	/**
	 * Set term can be used instead of addTerm if some more special
	 * values for a term have to be set.
	 *
	 * @param array $term Term array
	 * @return elastica\query\Term Current object
	 */
	public function setRawTerm(array $term) {
		return $this->setParams($term);
	}

	/**
	 * Adds a term to the term query
	 *
	 * @param string $key Key to query
	 * @param string|array $value Values(s) for the query. Boost can be set with array
	 * @param float $boost OPTIONAL Boost value (default = 1.0)
	 * @return elastica\query\Term Current object
	 */
	public function setTerm($key, $value, $boost = 1.0) {
		return $this->setRawTerm(array($key => array('value' => $value, 'boost' => $boost)));
	}
}
