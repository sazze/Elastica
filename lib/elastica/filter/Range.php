<?php
/**
 * Range Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/range-filter.html
 */
namespace elastica\filter;

class Range extends Abstract_
{
	protected $_fields = array();

	/**
	 * @param string $fieldName Field name
	 * @param array $args Field arguments
	 * @return elastica\filter\Range
	 */
	public function __construct($fieldName = false, array $args = array()) {

		if ($fieldName) {
			$this->addField($fieldName, $args);
		}
	}

	/**
	 * Ads a field with arguments to the range query
	 *
	 * @param string $fieldName Field name
	 * @param array $args Field arguments
	 * @return elastica\filter\Range
	 */
	public function addField($fieldName, array $args) {
		$this->_fields[$fieldName] = $args;
		return $this;
	}

	/**
	 * Convers object to array
	 *
	 * @see elastica\filter\Abstract_::toArray()
	 * @return array Filter array
	 */
	public function toArray() {
		$this->setParams($this->_fields);
		return parent::toArray();
	}
}
