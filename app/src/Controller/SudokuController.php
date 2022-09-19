<?php
namespace ApiSudoku\Controller;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SudokuController {
  public static function initRoutes($app) {
    $app->get('/sudoku', '\ApiSudoku\Controller\SudokuController:getAllSudokus');
    $app->get('/sudoku/{id:[0-9]+}', '\ApiSudoku\Controller\SudokuController:getSudokuById');
    $app->get('/player/{id:[0-9]+}', '\ApiSudoku\Controller\SudokuController:getPlayerById');
    $app->post('/player', '\ApiSudoku\Controller\SudokuController:postPlayer');
    $app->put('/player/{id:[0-9]+}', '\ApiSudoku\Controller\SudokuController:putPlayer');
    $app->patch('/player/{id:[0-9]+}', '\ApiSudoku\Controller\SudokuController:patchPlayer');
  }

  public function getAllSudokus(Request $request, Response $response, array $args) {
    $sudokus = \ApiSudoku\Model\Sudoku::getAllSudokus();
    if (is_null($sudokus)) {
      $response = $response->withStatus(500);
    }
    else {
      $response = $response->withJson($sudokus);
    }
    return $response;
  }

  public function getSudokuById(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sudoku = \ApiSudoku\Model\Sudoku::getSudokuById($id);
    if (is_null($sudoku)) {
      $response = $response->withStatus(404);
    }
    else {
      $response = $response->withJson($sudoku);
    }
    return $response;
  }

  public function getPlayerById(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $player = \ApiSudoku\Model\Player::getPlayerById($id);
    if (is_null($player)) {
      $response = $response->withStatus(404);
    }
    else {
      $response = $response->withJson($player);
    }
    return $response;
  }

  public function postPlayer(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    return $this->validatePersist($data, $response, null);
  }

  public function putPlayer(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    return $this->validatePersist($data, $response, $id);
  }

  public function patchPlayer(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $player = \ApiSudoku\Model\Player::getPlayerById($id);
    if (is_null($player)) {
      $response = $response->withStatus(404);
      return $response;
    }
    $data = $request->getParsedBody();
    // Pass existing data as default values
    if (!isset($data["username"])) $data["username"] = $player->getUsername();
    if (!isset($data["password"])) $data["password"] = $player->getPassword();
    if (!isset($data["email"])) $data["email"] = $player->getEmail();

    return $this->validatePersist($data, $response, $id);
  }

  private function validatePersist($data, $response, $id) {
    // Check all fields are set
    foreach(["username", "password", "email"] as $key) {
      if (!isset($data[$key])) {
        $response = $response->withStatus(400);
        return $response;
      }
    }
    // Validate username
    $username = trim($data["username"]);
    if (empty($username)) {
      $response = $response->withStatus(400);
      return $response;
    }
    $player = \ApiSudoku\Model\Player::getPlayerByUsername($username);
    if (!is_null($player) && $id != $player->getId()) {
      $response = $response->withStatus(400, "This player username already exists in another player");
      return $response;
    }
    // Validate password
    $password = trim($data["password"]);
    if (empty($password)) {
      $response = $response->withStatus(400);
      return $response;
    }
    // Validate email
    $email = trim($data["email"]);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response = $response->withStatus(400);
      return $response;
    }
    // All ok
    $data['id'] = $id;
    $player = \ApiSudoku\Model\Player::fromAssoc($data);
    $player = \ApiSudoku\Model\Player::persistPlayer($player);
    if (is_null($player)) {
      $response = $response->withStatus(500);
    }
    else {
      $response = $response->withJson($player);
    }
    return $response;
  }
}
