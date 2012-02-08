<?php
/**
 * Type Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author James Wilson <jwilson556@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/type-filter.html
 */
namespace elastica\filter;

class Type extends Abstract_
{
	protected $_type;

	/**
	 * @param string $typeName Type name
	 * @return elastica\filter\Type
	 */
	public function __construct($typeName = null) {
		if ($typeName) {
			$this->setType($typeName);
		}
	}

	/**
	 * Ads a field with arguments to the range query
	 *
	 * @param string $typeName Type name
	 * @return elastica\filter\Type current object
	 */
	public function setType($typeName) {
		$this->_type = $typeName;
		return $this;
	}

	/**
	 * Convert object to array
	 *
	 * @see elastica\filter\Abstract_::toArray()
	 * @return array Filter array
	 */
	public function toArray() {
		return array(
			'type' => array('value' => $this->_type)
		);
	}
}
