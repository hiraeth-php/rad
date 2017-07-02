<?php

namespace Hiraeth\RAD;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 *
 */
abstract class AbstractAction
{
	/**
	 *
	 */
	abstract public function __invoke(Request $request, $responder);
}
