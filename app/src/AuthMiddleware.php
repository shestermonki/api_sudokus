<?php
namespace ApiSudoku;

use ApiSudoku\Model\DB;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface {
  public function process(ServerRequestInterface $request, 
      RequestHandlerInterface $handler) : ResponseInterface {
    // Create a 401 response
    $responseFactory = new \Slim\Psr7\Factory\ResponseFactory();
    $response401 = $responseFactory->createResponse(401);

    // Get the token from header
    $token = $request->getHeader("Authorization");
    // No token => 401
    if (empty($token)) { sleep(1); return $response401; }
    // Decode the token string
    $token = $token[0];
    if (substr($token, 0, 7) != 'Bearer ') { sleep(1); return $response401; }
    $token = substr($token, 7);
    
    // Find user with this token
    $em = DB::getEM();
    $player = $em->getRepository('ApiSudoku\Model\Player')->findOneBy(['token' => $token]);
    if (is_null($player)) { sleep(1); return $response401; }

    // OK, add extra information to request
    $request->withAttribute('player', $player);

    // Call the next handler
    $response = $handler->handle($request);
    return $response;
  }
}