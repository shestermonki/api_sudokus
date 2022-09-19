<?php
namespace ApiSudoku\Model;

class Database {
  protected static ?\ApiSudoku\Model\Database $instance = null;

  public static function getInstance() : \ApiSudoku\Model\Database {
    if (is_null(static::$instance)) {
      static::$instance = new \ApiSudoku\Model\Database();
    }
    return static::$instance;
  }

  private \PDO $conn;

  protected function __construct() {
    $this->conn = new \PDO(
      "mysql:host=api_sudoku_db;dbname=sudokudb",
      "sudokuuser",
      "sudokupassword"
    );
  }

  public function getConnection() : \PDO { return $this->conn; }
}