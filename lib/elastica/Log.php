<?php
/**
 * Elastica log object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica;

class Log
{
	/**
	 * Log path or true if enabled
	 *
	 * @var string|bool
	 */
	protected $_log = false;

	/**
	 * @var string Last logged message
	 */
	protected $_lastMessage = '';

	/**
	 * Inits log object. Checks if logging is enabled for the given client
	 *
	 * @param elastica\Client $client
	 */
	public function __construct(Client $client) {
		$this->setLog($client->getConfig('log'));
	}

	/**
	 * @param string|elastica\Request $message
	 */
	public function log($message) {
		if ($message instanceof Request) {
			$message = $this->_convertRequest($message);
		}

		if ($this->_log) {
			$this->_lastMessage = $message;

			if (is_string($this->_log)) {
				error_log($message . PHP_EOL, 3, $this->_log);
			} else {
				error_log($message);
			}
		}
	}

	/**
	 * @param bool|string $log Enables log or sets log path
	 * @return elastica\Log
	 */
	public function setLog($log) {
		$this->_log = $log;
		return $this;
	}

	/**
	 * Converts a request to a log message
	 *
	 * @param elastica\Request $request
	 * @return string Request log message
	 */
	protected function _convertRequest(Request $request) {
		$message = 'curl -X' . strtoupper($request->getMethod()) . ' ';
		$message .= 'http://' . $request->getClient()->getHost() . ':' . $request->getClient()->getPort() . '/';
		$message .= $request->getPath();
		$message .= ' -d \'' . json_encode($request->getData()) . '\'';
		return $message;
	}

	/**
	 * @return string Last logged message
	 */
	public function getLastMessage() {
		return $this->_lastMessage;
	}
}
