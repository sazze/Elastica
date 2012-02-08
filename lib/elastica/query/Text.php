<?php
/**
 * Text query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/text-query.html
 */
namespace elastica\query;

class Text extends Abstract_
{
	/**
	 * Sets a param for the message array
	 *
	 * @param string $field
	 * @param mixed $values
	 * @return elastica\query\Text
	 */
	public function setField($field, $values) {
		return $this->setParam($field, $values);
	}

	/**
	 * Sets a param for the given field
	 *
	 * @param string $field
	 * @param string $key
	 * @param string $value
	 * @return elastica\query\Text
	 */
	public function setFieldParam($field, $key, $value) {
		if (!isset($this->_params[$field])) {
			$this->_params[$field] = array();
		}

		$this->_params[$field][$key] = $value;
		return $this;
	}

	/**
	 * Sets the query string
	 *
	 * @param string $field
	 * @param string $query
	 * @return elastica\query\Text
	 */
	public function setFieldQuery($field, $query) {
		return $this->setFieldParam($field, 'query', $query);
	}

	/**
	 * @param string $field
	 * @param string $type Text query type
	 * @return elastica\query\Text
	 */
	public function setFieldType($field, $type) {
		return $this->setFieldParam($field, 'type', $type);
	}

	/**
	 * @param string $field
	 * @param int $maxExpansions
	 * @return elastica\query\Text
	 */
	public function setFieldMaxExpansions($field, $maxExpansions) {
		return $this->setFieldParam($field, 'max_expansions', $maxExpansions);
	}
}
