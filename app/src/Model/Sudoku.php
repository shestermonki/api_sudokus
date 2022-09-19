<?php

namespace ApiSudoku\Model;

class Sudoku implements \JsonSerializable {
  private ?int $id;
  private int $level;
  private string $problem;
  private string $solved;

  public function __construct(?int $id, int $level, string $problem, string $solved) {
    $this->id = $id;
    $this->level = $level;
    $this->problem = $problem;
    $this->solved = $solved;
  }

  public function getId() : ?int { return $this->id; }
  public function setId(?int $id) { $this->id = $id; }
  public function getLevel() : int { return $this->level; }
  public function setLevel(int $level) { $this->level = $level; }
  public function getProblem() : string { return $this->problem; }
  public function setProblem(string $problem) { $this->problem = $problem; }
  public function getSolved() : string { return $this->solved; }
  public function setSolved(string $solved) { $this->solved = $solved; }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data) : Sudoku {
    return new \ApiSudoku\Model\Sudoku(
      $data['id'], 
      $data['level'],
      $data['problem'], 
      $data['solved']
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'level' => $this->level,
      'problem' => $this->problem,
      'solved' => $this->solved,
    ];
  }

  // DAO METHODS
  public static function insertSudoku(\ApiSudoku\Model\Sudoku $sudoku) : ?\ApiSudoku\Model\Sudoku {
    $sql = "INSERT INTO sudokus VALUES (:id, :level, :problem, :solved)";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => null,
      ':level' => $sudoku->getLevel(),
      ':problem' => $sudoku->getProblem(),
      ':solved' => $sudoku->getSolved()
    ]);
    $id = $conn->lastInsertId();
    return static::getSudokuById($id);
  }

  public static function getAllSudokus() : array {
    $sql = "SELECT * FROM sudokus";
    $conn = Database::getInstance()->getConnection();
    $result = $conn->query($sql);
    $sudokusAssoc = $result->fetchAll(\PDO::FETCH_ASSOC);
    if (!$sudokusAssoc) return [];
    $sudokus = [];
    foreach($sudokusAssoc as $sudokuAssoc) {
      $sudokus[] = \ApiSudoku\Model\Sudoku::fromAssoc($sudokuAssoc);
    }
    return $sudokus;
  }

  public static function getSudokuById(int $id) : ?\ApiSudoku\Model\Sudoku {
    $sql = "SELECT * FROM sudokus WHERE id=:id";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $id
    ]);
    $sudokuAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$sudokuAssoc) return null;
    $sudoku = \ApiSudoku\Model\Sudoku::fromAssoc($sudokuAssoc);
    return $sudoku;
  }

  public static function deleteSudokuById(int $id) : bool {
    $sql = "DELETE FROM sudokus WHERE id=:id";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $id
    ]);
    return $result;
  }

  public static function deleteSudoku(\ApiSudoku\Model\Sudoku $sudoku) : bool {
    $sql = "DELETE FROM sudokus WHERE id=:id";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $sudoku->getId()
    ]);
    return $result;
  }
}