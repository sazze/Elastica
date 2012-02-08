<?php
/**
 * Implements the Histogram facet.
 *
 * @category Xodoa
 * @package elastica
 * @author Raul Martinez Jr  <juneym@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/api/search/facets/histogram-facet.html
 */
namespace elastica\facet;

class Histogram extends Abstract_
{
	/**
	 * Sets the field for histogram
	 *
	 * @param string $field The name of the field for the historgram
	 * @return elastica\facet\Histogram
	 */
	public function setField($field) {
		return $this->setParam('field', $field);
	}

	/**
	 * Set the value for interval
	 *
	 * @param string $interval
	 * @return elastica\facet\Range
	 */
	public function setInterval($interval) {
		return $this->setParam('interval', $interval);
	}


	/**
	 * Set the fields for key_field and value_field
	 *
	 * @param string $keyField Key field
	 * @param string $valueField Value field
	 * @return elastica\facet\Range
	 */
	public function setKeyValueFields($keyField, $valueField) {
		return $this->setParam('key_field', $keyField)->setParam('value_field', $valueField);
	}

	/**
	 * Sets the key and value for this facet by script.
	 *
	 * @param string $keyScript Script to check whether it falls into the range.
	 * @param string $valueScript Script to use for statistical calculations.
	 */
	public function setKeyValueScripts($keyScript, $valueScript) {
		return $this->setParam('key_script', $keyScript)
					->setParam('value_script', $valueScript);
	}

	/**
	 * Set the "params" essential to the a script
	 *
	 * @param array $params Associative array (key/value pair)
	 * @return elastica\facet\Histogram Facet object
	 */
	public function setScriptParams(Array $params) {
		return $this->setParam('params', $params);
	}

	/**
	 * Creates the full facet definition, which includes the basic
	 * facet definition of the parent.
	 *
	 * @see elastica\facet\Abstract_::toArray()
	 * @throws \elastica\exception\Invalid When the right fields haven't been set.
	 * @return array
	 */
	public function toArray() {
		/**
		 * Set the range in the abstract as param.
		 */
		$this->_setFacetParam('histogram', $this->_params);
		return parent::toArray();
	}
}
