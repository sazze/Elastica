<?php
/**
 * Range query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/range_query/
 */
namespace elastica\query;

class Range extends Abstract_
{
	/**
	 * Adds a range field to the query
	 *
	 * @param string $fieldName Field name
	 * @param array $args Field arguments
	 * @return elastica\query\Range Current object
	 */
	public function addField($fieldName, array $args) {
		return $this->setParam($fieldName, $args);

	}
}
