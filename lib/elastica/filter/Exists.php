<?php
/**
 * Exists query
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Oleg Cherniy <oleg.cherniy@gmail.com>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/exists-filter.html
 */
namespace elastica\filter;

class Exists extends Abstract_
{
	/**
	 * @param string $field
	 */
	public function __construct($field) {
		$this->setField($field);
	}

	/**
	 * @param string $field
	 * @return elastica\filter\Exists
	 */
	public function setField($field) {
		return $this->setParam('field', $field);
	}
}
