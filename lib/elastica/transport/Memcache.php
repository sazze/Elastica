<?php
/**
 * Elastica Memcache Transport object
 *
 * @category Xodoa
 * @package elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
namespace elastica\transport;

class Memcache extends Abstract_ {

	/**
	 * Makes calls to the elasticsearch server
	 *
	 * @param string $host Host name
	 * @param int $port Port number
	 * @return elastica\Response Response object
	 */
	public function exec(array $params) {

		$request = $this->getRequest();

		$memcache = new \Memcache();
		$memcache->connect($params['host'], $params['port']);

		// Finds right function name
		$function = strtolower($request->getMethod());

		$data = $request->getData();

		$content = '';

		if (!empty($data)) {
			if (is_array($data)) {
				$content = json_encode($data);
			} else {
				$content = $data;
			}

			// Escaping of / not necessary. Causes problems in base64 encoding of files
			$content = str_replace('\/', '/', $content);
		}

		$responseString = '';

		switch($function) {
			case 'post':
			case 'put':
				$memcache->set($request->getPath(), $content);
				break;
			case 'get':
				$responseString = $memcache->get($request->getPath() . '?source=' . $content);
				echo $responseString . PHP_EOL;
				break;
			case 'delete':
				break;
			default:
				throw new \elastica\exception\Invalid('Method ' . $function . ' is not supported in memcache transport');

		}


		$response = new \elastica\Response($responseString);

		if (defined('DEBUG') && DEBUG) {
			$response->setQueryTime($end - $start);
			$response->setTransferInfo(curl_getinfo($conn));
		}

		if ($response->hasError()) {
			throw new \elastica\exception\Response($response);
		}

		return $response;
	}
}
