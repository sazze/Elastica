<?php
/**
 * Elastica query object
 *
 * Creates different types of queries
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/
 */
namespace elastica;

class Query extends Param
{
	protected $_params = array();

	/**
	 * Creates a query object
	 *
	 * @param array|elastica\query\Abstract_ $query OPTIONAL Query object (default = null)
	 */
	public function __construct($query = null) {
		if (is_array($query)) {
			$this->setRawQuery($query);
		} else if ($query instanceof query\Abstract_) {
			$this->setQuery($query);
		}
	}

	/**
	 * Transforms a string or an array to a query object
	 *
	 * If query is empty,
	 *
	 * @param mixed $query
	 * @return elastica\Query
	 **/
	public static function create($query) {
		switch (true) {
			case $query instanceof Query:
				return $query;
			case $query instanceof query\Abstract_:
				return new self($query);
			case $query instanceof filter\Abstract_:
				$newQuery = new Query();
				$newQuery->setFilter($query);
				return $newQuery;
			case empty($query):
				return new self(new query\MatchAll());
			case is_string($query):
				return new self(new query\QueryString($query));

		}

		// TODO: Implement queries without
		throw new exception\NotImplemented();
	}

	/**
	 * Sets query as raw array. Will overwrite all already set arguments
	 *
	 * @param array $query Query array
	 * @return elastica\Query Query object
	 */
	public function setRawQuery(array $query) {
		$this->_params = $query;
		return $this;
	}

	/**
	 * Sets the query
	 *
	 * @param elastica\query\Abstract_ $query Query object
	 * @return elastica\query Query object
	 */
	public function setQuery(query\Abstract_ $query) {
		return $this->setParam('query', $query->toArray());
	}

	/**
	 * Gets the query array
	 *
	 * @return array
	 **/
	public function getQuery() {
		return $this->getParam('query');
	}

	/**
	 * @param elastica\filter\Abstract_ $filter Filter object
	 * @return elastica\Query Current object
	 */
	public function setFilter(filter\Abstract_ $filter) {
		return $this->setParam('filter', $filter->toArray());
	}

	/**
	 * Sets the start from which the search results should be returned
	 *
	 * @param int $from
	 * @return elastica\Query Query object
	 */
	public function setFrom($from) {
		return $this->setParam('from', $from);
	}

	/**
	 * Sets sort arguments for the query
	 * Replaces existing values
	 *
	 * @param array $sortArgs Sorting arguments
	 * @return elastica\Query Query object
	 * @link http://www.elasticsearch.org/guide/reference/api/search/sort.html
	 */
	public function setSort(array $sortArgs) {
		return $this->setParam('sort', $sortArgs);
	}

	/**
	 * Adds a sort param to the query
	 *
	 * @param mixed $sort Sort parameter
	 * @return elastica\Query Query object
	 * @link http://www.elasticsearch.org/guide/reference/api/search/sort.html
	 */
	public function addSort($sort) {
		return $this->addParam('sort', $sort);
	}

	/**
	 * Sets highlight arguments for the query
	 *
	 * @param array $highlightArgs Set all highlight arguments
	 * @return elastica\Query Query object
	 * @link http://www.elasticsearch.org/guide/reference/api/search/highlighting.html
	 */
	public function setHighlight(array $highlightArgs) {
		return $this->setParam('highlight', $highlightArgs);
	}

	/**
	 * Adds a highlight argument
	 *
	 * @param mixed $highlight Add highlight argument
	 * @return elastica\Query Query object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/highlighting/
	 */
	public function addHighlight($highlight) {
		return $this->addParam('highlight', $highlight);
	}

	/**
	 * Alias for setLimit
	 *
	 * @param int $limit OPTIONAL Maximal number of results for query (default = 10)
	 * @return elastica\Query Query object
	 */
	public function setSize($limit = 10) {
		return $this->setLimit($limit);
	}

	/**
	 * Sets maximum number of results for this query
	 *
	 * Setting the limit to 0, means no limit
	 *
	 * @param int $limit OPTIONAL Maximal number of results for query (default = 10)
	 * @return elastica\Query Query object
	 */
	public function setLimit($limit = 10) {
		return $this->setParam('size', $limit);
	}

	/**
	 * Enables explain on the query
	 *
	 * @param bool $explain OPTIONAL Enabled or disable explain (default = true)
	 * @return elastica\Query Current object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/explain/
	 */
	public function setExplain($explain = true) {
		return $this->setParam('explain', $explain);
	}

	/**
	 * Enables version on the query
	 *
	 * @param bool $version OPTIONAL Enabled or disable version (default = true)
	 * @return elastica\Query Current object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/version/
	 */
	public function setVersion($version = true) {
		return $this->setParam('version', $version);
	}

	/**
	 * Sets the fields to be returned by the search
	 *
	 * @param array $fields Fields to be returne
	 * @return elastica\Query Current object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/fields/
	 */
	public function setFields(array $fields) {
		return $this->setParam('fields', $fields);
	}

	/**
	 * Set script fields
	 *
	 * @param array $scriptFields Script fields
	 * @return elastica\Query Current object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/script_fields/
	 */
	public function setScriptFields(array $scriptFields) {
		return $this->setParam('script_fields', $scriptFields);
	}

	/**
	 * Sets all facets for this query object. Replaces existing facets
	 *
	 * @param array $facets List of facet objects
	 * @return elastica\Query Query object
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/facets
	 */
	public function setFacets(array $facets) {
		$this->_params['facets'] = array();
		foreach ($facets as $facet) {
			$this->addFacet($facet);
		}
		return $this;
	}

	/**
	 * Adds a Facet to the query
	 *
	 * @param elastica\facet\Abstract_ $facet Facet object
	 * @return elastica\Query Query object
	 */
	public function addFacet(facet\Abstract_ $facet) {
		$this->_params['facets'][$facet->getName()] = $facet->toArray();
		return $this;
	}

	/**
	 * Converts all query params to an array
	 *
	 * @return array Query array
	 */
	public function toArray() {

		// If no query is set, all query is chosen by default
		if (!isset($this->_params['query'])) {
			$this->setQuery(new query\MatchAll());
		}

		return $this->_params;
	}

	/**
	 * Allows filtering of documents based on a minimum score
	 *
	 * @param int|double $minScore Minimum score to filter documents by
	 * @return elastica\Query Query object
	 */
	public function setMinScore($minScore) {
		if (!is_numeric($minScore)) {
			throw new exception\Invalid('has to be numeric param');
		}

		return $this->setParam('min_score', $minScore);
	}
}
