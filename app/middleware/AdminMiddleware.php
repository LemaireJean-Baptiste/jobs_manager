<?php
namespace App\Middleware;
class AdminMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		if(!$this->container->auth->check(true)){
			$this->container->flash->addmessage('error', 'you are not administrator.');
			return $response->withRedirect($this->container->router->pathFor('home'));

    	}
      	
		$response = $next($request, $response);
		return $response;
	}
}
