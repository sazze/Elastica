<?php
/**
 * Abstract_ filter object. Should be extended by all filter types
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\filter;

abstract class Abstract_ extends \elastica\Param
{
	/**
	 * Sets the filter cache to true.
	 * This is still experimental
	 */
	public function setCached() {
		$this->_setRawParam('_cache', true);
	}
}
