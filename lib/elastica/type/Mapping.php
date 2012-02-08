<?php
/**
 * Elastica Mapping object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 * @link http://www.elasticsearch.org/guide/reference/mapping/
 */
namespace elastica\type;

class Mapping
{
	protected $_mapping = array();

	protected $_type = null;

	/**
	 * @param elastica\Type $type OPTIONAL Type object
	 * @param array $properties OPTIONAL Properties
	 */
	public function __construct(\elastica\Type $type = null, array $properties = array()) {
		if ($type) {
			$this->setType($type);
		}

		if (!empty($properties)) {
			$this->setProperties($properties);
		}
	}

	/**
	 * Sets the mapping type
	 * Enter description here ...
	 * @param elastica\Type $type Type object
	 * @return elastica\type\Mapping Current object
	 */
	public function setType(\elastica\Type $type) {
		$this->_type = $type;
		return $this;
	}

	/**
	 * Sets the mapping properites
	 *
	 * @param array $properties Prpoerties
	 * @return elastica\type\Mapping Mapping object
	 */
	public function setProperties(array $properties) {
		return $this->setParam('properties', $properties);
	}

	/**
	 * Returns mapping type
	 *
	 * @return elastica\Type Type
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * Sets source values
	 *
	 * To disable source, argument is
	 * array('enabled' => false)
	 *
	 * @param array $source Source array
	 * @return elastica\type\Mapping Current object
	 * @link http://www.elasticsearch.org/guide/reference/mapping/source-field.html
	 */
	public function setSource(array $source) {
		return $this->setParam('_source', $source);
	}

	/**
	 * Disables the source in the index
	 *
	 * Param can be set to true to enable again
	 *
	 * @param bool $enabled OPTIONAL (default = false)
	 * @return elastica\type\Mapping Current object
	 */
	public function disableSource($enabled = false) {
		return $this->setSource(array('enabled' => $enabled));
	}

	/**
	 * Sets raw parameters
	 *
	 * Possible options:
	 * _uid
	 * _id
	 * _type
	 * _source
	 * _all
	 * _analyzer
	 * _boost
	 * _parent
	 * _routing
	 * _index
	 * _size
	 * properties
	 *
	 * @param string $key Key name
	 * @param mixed $value Key value
	 * @return elastica\type\Mapping Current object
	 */
	public function setParam($key, $value) {
		$this->_mapping[$key] = $value;
		return $this;
	}

	/**
	 * @param array $params TTL Params (enabled, default, ...)
	 * @return elastica\type\Mapping
	 */
	public function setTTL(array $params) {
		return $this->setParam('_ttl', $params);

	}

	/**
	 * Enables TTL for all documens in this type
	 *
	 * @param bool $enabled OPTIONAL (default = true)
	 * @return elastica\type\Mapping
	 */
	public function enableTTL($enabled = true) {
		return $this->setTTL(array('enabled' => $enabled));
	}

	/**
	 * Converts the mapping to an array
	 *
	 * @return array Mapping as array
	 */
	public function toArray() {
		$type = $this->getType();

		if (empty($type)) {
			throw new \elastica\exception\Invalid('Type has to be set');
		}

		return array($type->getType() => $this->_mapping);
	}

	/**
	 * Submits the mapping and sends it to the server
	 *
	 * @return elastica\Response Response object
	 */
	public function send() {
		$path = '_mapping';
		return $this->getType()->request($path, \elastica\Request::PUT, $this->toArray());
	}

	/**
	 * Creates a mapping object
	 *
	 * @param array|elastica\type\Mapping $mapping Mapping object or properties array
	 * @return elastica\type\Mapping Mapping object
	 * @throws \elastica\exception\Invalid If invalid type
	 */
	public static function create($mapping) {

		if (is_array($mapping)) {
			$mappingObject = new Mapping();
			$mappingObject->setProperties($mapping);
		} else {
			$mappingObject = $mapping;
		}

		if (!$mappingObject instanceof Mapping) {
			throw new \elastica\exception\Invalid('Invalid object type');
		}

		return $mappingObject;
	}
}
