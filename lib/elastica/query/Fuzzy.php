<?php
/**
 * Fuzzy query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/fuzzy_query/
 */
namespace elastica\query;

class Fuzzy extends Abstract_
{
	/**
	 * Adds field to fuzzy query
	 *
	 * @param string $fieldName Field name
	 * @param array $args Data array
	 * @return elastica\query\Fuzzy Current object
	 */
	public function addField($fieldName, array $args) {
		return $this->setParam($fieldName, $args);
	}
}
