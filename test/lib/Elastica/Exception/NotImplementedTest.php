<?php

require_once dirname(__FILE__) . '/../../../bootstrap.php';

class Elastica_Exception_NotImplementedTest extends PHPUnit_Framework_TestCase
{
	public function setUp() {
	}

	public function tearDown() {

	}

	public function testInstance() {
		$code = 4;
		$message = 'Hello world';
		$exception = new elastica\exception\NotImplemented($message, $code);

		$this->assertInstanceOf('elastica\exception\NotImplemented', $exception);
		$this->assertInstanceOf('elastica\exception\Abstract_', $exception);
		$this->assertInstanceOf('Exception', $exception);

		$this->assertEquals($message, $exception->getMessage());
		$this->assertEquals($code, $exception->getCode());
	}
}
