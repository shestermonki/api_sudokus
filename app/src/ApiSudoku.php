<?php
namespace ApiSudoku;

use Slim\Factory\AppFactory;

class ApiSudoku {
  public static function processRequest() {
    $app = AppFactory::create();
    \ApiSudoku\Controller\HelloController::initRoutes($app);
    \ApiSudoku\Controller\ColorController::initRoutes($app);
    \ApiSudoku\Controller\SudokuController::initRoutes($app);
    $authMiddleware = new AuthMiddleware();
    $app->add($authMiddleware);
    $app->run();
  }
}