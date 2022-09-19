<?php
namespace ApiSudoku\Controller;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HelloController {
  public static function initRoutes($app) {
    $app->get('/hello/{name}', '\ApiSudoku\Controller\HelloController:getHello');
  }

  public function getHello(Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
  }
}