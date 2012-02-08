<?php

/**
 * Missing Filter
 *
 * @uses elastica\filter\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Maciej Wiercinski <maciej@wiercinski.net>
 * @link http://www.elasticsearch.org/guide/reference/query-dsl/missing-filter.html
 */
namespace elastica\filter;

class Missing extends Abstract_
{
	/**
	 * @param string $field OPTIONAL
	 */
	public function __construct($field = '') {
		if (strlen($field)) {
			$this->setField($field);
		}
	}

	/**
	 * @param string $field
	 */
	public function setField($field) {
		return $this->setParam('field', (string) $field);
	}
}
