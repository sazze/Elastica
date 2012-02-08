<?php
/**
 * Field query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/field_query/
 */
namespace elastica\query;

class Field extends Abstract_
{
	protected $_field = '';
	protected $_queryString = '';

	/**
	 * Creates field query object. Calls setField and setQuery with argument
	 *
	 * @param string $field OPTIONAL field for object
	 * @param string $queryString OPTIONAL Query string for object
	 */
	public function __construct($field = '', $queryString = '') {
		$this->setField($field);
		$this->setQueryString($queryString);
	}

	/**
	 * Sets the field
	 *
	 * @param string $field Field
	 * @return elastica\query\Field Current object
	 */
	public function setField($field) {
		$this->_field = $field;
		return $this;
	}

	/**
	 * Sets a new query string for the object
	 *
	 * @param string $queryString Query string
	 * @return elastica\query\Field Current object
	 */
	public function setQueryString($queryString) {
		if (!is_string($queryString)) {
			throw new \elastica\exception\Invalid('Parameter has to be a string');
		}

		$this->_queryString = $queryString;
		return $this;
	}

	/**
	 * Converts query to array
	 *
	 * @return array Query array
	 * @see elastica\query\Abstract_::toArray()
	 */
	public function toArray() {
		$this->setParam($this->_field, array('query' => $this->_queryString));
		return parent::toArray();
	}
}
