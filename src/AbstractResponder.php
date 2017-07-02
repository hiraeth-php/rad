<?php

namespace Hiraeth\RAD;

use Journey\Router as Router;
use Psr\Http\Message\StreamInterface as Stream;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
abstract class AbstractResponder
{
	/**
	 *
	 */
	protected $action = NULL;


	/**
	 *
	 */
	protected $data = array();


	/**
	 *
	 */
	protected $request = NULL;


	/**
	 *
	 */
	protected $response = NULL;


	/**
	 *
	 */
	protected $stream = NULL;


	/**
	 *
	 */
	abstract public function render();


	/**
	 *
	 */
	public function __invoke(Router $router, Stream $stream)
	{
		$this->response = $router->getResponse();
		$this->request  = $router->getRequest();
		$this->stream   = $stream;

		if ($action = $this->action) {
			$action($this->request, $this);
		}

		$output = $this->render($this->request);

		if (!$output instanceof Response) {
			if (!$output instanceof Stream) {
				$this->stream->write((string) $output);

				$output = $this->stream;
			}

			$output = $this->response->withBody($output);
		}

		return $output;
	}


	/**
	 *
	 */
	public function set(array $data)
	{
		$this->data = $this->data + $data;
	}
}
