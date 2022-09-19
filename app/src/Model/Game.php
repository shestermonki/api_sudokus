<?php
namespace ApiSudoku\Model;

use \ApiSudoku\Model\Sudoku as SudokuClass;

class Game {
  private int $id;
  private SudokuClass $sudoku;
  private \ApiSudoku\Model\Player $player;
  private string $current;
  private int $totalTime;
  private int $finished;

  public function __construct(
      int $id, 
      \ApiSudoku\Model\Sudoku $sudoku, 
      \ApiSudoku\Model\Player $player, 
      string $current  = "",
      int $totalTime = 0, 
      int $finished = 0) {
    $this->id = $id;
    $this->sudoku = $sudoku;
    $this->player = $player;
    $this->current = empty($current) ? $sudoku->getProblem() : $current;
    $this->totalTime = $totalTime;
    $this->finished = $finished;
  }

  public function getId() : int { return $this->id; }
  public function setId(int $id) { $this->id = $id; }
  public function getSudoku() : \Sudoku\Model\Sudoku { return $this->sudoku; }
  public function setSudoku(\Sudoku\Model\Sudoku $sudoku) { $this->sudoku = $sudoku; }
  public function getPlayer() : \Sudoku\Model\Player { return $this->player; }
  public function setPlayer(\Sudoku\Model\Player $player) { $this->player = $player; }
  public function getCurrent() : string { return $this->current; }
  public function setCurrent(string $current) { $this->current = $current; }
  public function getTotalTime() : int { return $this->totalTime; }
  public function setTotalTime(int $totalTime) { $this->totalTime = $totalTime; }
  public function isFinished() : bool { return ($this->finished == 1); }
  public function getFinished() : int { return $this->finished; }
  public function setFinished(int $finished) { $this->finished = $finished; }

  // Mètode Game->isSolved() per saber si una partida està solucionada (i és correcte)
  public function isSolved() : bool {
    return ($this->current == $this->sudoku->getSolved());
  }

  // Mètode Game->hasErrors() per saber si en una partida hi ha algun error per part del jugador.
  public function hasErrors() : bool {
    $solved = $this->sudoku->getSolved();
    for($i = 0; $i < 81; $i++) {
      if ($this->current[$i] == '.') continue;
      if ($this->current[$i] != $solved[$i]) return true;
    }
    return false;
  }
}