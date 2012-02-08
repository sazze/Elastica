<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Elastica_ParamTest extends Elastica_Test
{
	/**
	 * Tests if filter name is set correct and instance is created
	 */
	public function testInstance() {
		$this->markTestSkipped('Not sure why this one is erroring out.');
	
		$className = 'elastica\Param';
		$param = $this->getMock('elastica\Param', null, array(), $className);

		$this->assertInstanceOf('elastica\Param', $param);
		$this->assertEquals(array('param_abstract' => array()), $param->toArray());
	}

	public function testToArrayEmpty() {
		$param = new elastica\Param();
		$this->assertInstanceOf('elastica\Param', $param);
		$this->assertEquals(array($this->_getFilterName($param) => array()), $param->toArray());
	}

	public function testSetParams() {
		$param = new elastica\Param();
		$params = array('hello' => 'word', 'nicolas' => 'ruflin');
		$param->setParams($params);

		$this->assertInstanceOf('elastica\Param', $param);
		$this->assertEquals(array($this->_getFilterName($param) => $params), $param->toArray());
	}

	public function testSetGetParam() {
		$param = new elastica\Param();

		$key = 'name';
		$value = 'nicolas ruflin';

		$params = array($key => $value);
		$param->setParam($key, $value);

		$this->assertEquals($params, $param->getParams());
		$this->assertEquals($value, $param->getParam($key));
	}

	public function testAddParam() {
		$param = new elastica\Param();

		$key = 'name';
		$value = 'nicolas ruflin';

		$param->addParam($key, $value);

		$this->assertEquals(array($key => array($value)), $param->getParams());
		$this->assertEquals(array($value), $param->getParam($key));
	}

	public function testAddParam2() {
		$param = new elastica\Param();

		$key = 'name';
		$value1 = 'nicolas';
		$value2 = 'ruflin';

		$param->addParam($key, $value1);
		$param->addParam($key, $value2);

		$this->assertEquals(array($key => array($value1, $value2)), $param->getParams());
		$this->assertEquals(array($value1, $value2), $param->getParam($key));
	}

	public function testGetParamInvalid() {
		$param = new elastica\Param();

		try {
			$param->getParam('notest');
			$this->fail('Should throw exception');
		} catch(elastica\exception\Invalid $e) {
			$this->assertTrue(true);
		}
	}

	protected function _getFilterName($filter) {
		// Picks the last part of the class name and makes it snake_case
		$classNameParts = explode('\\', get_class($filter));
		return elastica\Util::toSnakeCase(array_pop($classNameParts));
	}
}
