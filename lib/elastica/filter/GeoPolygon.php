<?php

/**
 * Geo polygon filter
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Michael Maclean <mgdm@php.net>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/geo_bounding_box_filter/
 */
namespace elastica\filter;

class GeoPolygon extends Abstract_
{
	protected $_key;
	protected $_points;

	/**
	 * @param string $key Key
	 * @param array $points Points making up polygon
	 */
	public function __construct($key, array $points) {
		$this->_key = $key;
		$this->_points = $points;
	}

	/**
	 * Converts filter to array
	 *
	 * @see elastica\filter\Abstract_::toArray()
	 * @return array
	 */
	public function toArray() {
		return array(
			'geo_polygon' => array(
				$this->_key => array(
					'points' => $this->_points
				),
			)
		);
	}
}
