<?php

/**
 * Geo bounding box filter
 *
 * @uses elastica\query\Abstract_
 * @category Xodoa
 * @package elastica
 * @author Fabian Vogler <fabian@equivalence.ch>
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/query_dsl/geo_bounding_box_filter/
 */
namespace elastica\filter;

class GeoBoundingBox extends Abstract_
{

	/**
	 * @param string $key Key
	 * @param array $coordinates Array with top left coordinate as first and bottom right coordinate as second element
	 */
	public function __construct($key, array $coordinates) {
		$this->addCoordinates($key, $coordinates);
	}

	/**
	 * @param string $key Key
	 * @param array $coordinates Array with top left coordinate as first and bottom right coordinate as second element
	 * @throws \elastica\exception\Invalid If $coordinates doesn't have two elements
	 * @return elastica\filter\GeoBoundingBox Current object
	 */
	public function addCoordinates($key, array $coordinates) {

		if (!isset($coordinates[0]) || !isset($coordinates[1])) {
			throw new \elastica\exception\Invalid('expected $coordinates to be an array with two elements');
		}

		$this->setParam($key, array(
			'top_left' => $coordinates[0],
			'bottom_right' => $coordinates[1]
		));

		return $this;
	}
}
