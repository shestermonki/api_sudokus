<?php

namespace ApiSudoku\Model;

class ColorValidator {
  public $color;

  public function __construct() {
    $this->color = new Color();
  }

  public function loadColor($color) {
    $this->color = $color;
  }

  public function mergeData($data, $nullIfNotSet = true) {
    foreach(['name', 'red', 'green', 'blue'] as $key) {
      if (isset($data[$key])) {
        $this->color->$key = trim($data[$key]);
      }
      else if ($nullIfNotSet) {
        $this->color->$key = null;
      }
    }
  }

  // Validate integrity of data
  public function validate() {
    // Check all fields are set
    foreach(["name", "red", "green", "blue"] as $key) {
      if (!isset($this->color->$key) || is_null($this->color->$key)) return false;
    }
    // Validate numbers
    foreach(["red", "green", "blue"] as $key) {
      $value = (int)$this->color->$key;
      $this->color->$key = $value;
      if (!is_numeric($value) || ($value < 0) || ($value > 255)) {
        return false;
      }
    }
    // Validate name
    $this->color->name = trim($this->color->name);
    if (empty($this->color->name)) return false;
    $em = DB::getEM();
    $other = $em->getRepository('ApiSudoku\Model\Color')->findOneBy(['name' => $this->color->name]);
    if (!is_null($other) && is_null($this->color->id)) return false;
    if (!is_null($other) && ($other->id != $this->color->id)) return false;
    return true;
  }

  public function validatePersist() {
    $ok = $this->validate();
    if (!$ok) return null;
    $em = DB::getEM();
    $em->persist($this->color);
    $em->flush();
    return $this->color;
  }
}