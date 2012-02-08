<?php
/**
 * Prefix filter
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Jasper van Wanrooy <jasper@vanwanrooy.net>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/prefix-filter.html
 */
namespace elastica\filter;

class Prefix extends Abstract_
{
	/**
	 * Holds the name of the field for the prefix.
	 *
	 * @var string
	 */
	protected $_field = '';

	/**
	 * Holds the prefix string.
	 *
	 * @var string
	 */
	protected $_prefix = '';

	/**
	 * Creates prefix filter
	 *
	 * @param string $field Field name
	 * @param string $prefix Prefix string
	 */
	public function __construct($field = '', $prefix = '') {
		$this->setField($field);
		$this->setPrefix($prefix);
	}

	/**
	 * Sets the name of the prefix field.
	 *
	 * @param string $field Field name
	 */
	public function setField($field) {
		$this->_field = $field;
		return $this;
	}

	/**
	 * Sets the prefix string.
	 *
	 * @param string $prefix Prefix string
	 */
	public function setPrefix($prefix) {
		$this->_prefix = $prefix;
		return $this;
	}

	/**
	 * Convers object to an arrray
	 *
	 * @see elastica\filter\Abstract_::toArray()
	 * @return array data array
	 */
	public function toArray() {
		$this->setParam($this->_field, $this->_prefix);
		return parent::toArray();
	}
}
