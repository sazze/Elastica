<?php
/**
 * Array query
 * Pure php array query. Can be used to create any not existing type of query.
 *
 * @uses elastica\query\Abstract
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\query;

class Array_ extends Abstract_
{
	protected $_query = array();

	/**
	 * Constructs a query based on an array
	 *
	 * @param array $query Query array
	 */
	public function __construct(array $query) {
		$this->setQuery($query);
	}

	/**
	 * Sets new query array
	 *
	 * @param array $query Query array
	 * @return elastica\query\Array_ Current object
	 */
	public function setQuery(array $query) {
		$this->_query = $query;
		return $this;
	}

	/**
	 * Converts query to array
	 *
	 * @return array Query array
	 * @see elastica\query\Abstract::toArray()
	 */
	public function toArray() {
		return $this->_query;
	}
}
