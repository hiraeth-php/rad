<?php

namespace Hiraeth\RAD;

use Journey\Router as Router;
use Twig\Environment as Twig;
use Psr\Http\Message\StreamInterface as Stream;
use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class TwigResponder extends AbstractResponder
{
	/**
	 *
	 */
	protected $templatePath = NULL;


	/**
	 *
	 */
	protected $twig = NULL;


	/**
	 *
	 */
	public function __invoke(Router $router, Stream $stream, Twig $twig = NULL)
	{
		$this->twig = $twig;

		if (!$this->twig) {
			throw new RuntimeException('Twig\Environment is not available, please install hiraeth/twig.');
		}

		return parent::__invoke($router, $stream);
	}


	/**
	 *
	 */
	public function render()
	{
		if (!$this->templatePath) {
			$this->templatePath = $this->request->getUri()->getPath();
		}

		if (substr($this->templatePath, -1) == '/') {
			$template = '@pages' . $this->templatePath . 'index.html';
		} else {
			$template = '@pages' . $this->templatePath . '.html';
		}

		try {
			$template  = $this->twig->load($template);
			$byte_size = $this->stream->write($template->render($this->data));

			return $this->response->withStatus(200)->withBody($this->stream);

		} catch (\Twig\Error\LoaderError $e) {
			return $this->response->withStatus(404);
		}
	}
}
