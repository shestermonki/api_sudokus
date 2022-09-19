<?php

namespace ApiSudoku\Model;

class ColorNameValidator {
  public $colorName;

  public function __construct() {
    $this->colorName = new ColorName();
  }

  public function loadColorName($colorName) {
    $this->colorName = $colorName;
  }

  public function setColor($color) {
    $this->colorName->color = $color;
  }

  public function mergeData($data, $nullIfNotSet = true) {
    foreach(['language', 'name'] as $key) {
      if (isset($data[$key])) {
        $this->colorName->$key = trim($data[$key]);
      }
      else if ($nullIfNotSet) {
        $this->colorName->$key = null;
      }
    }
  }

  // Validate integrity of data
  public function validate() {
    if (is_null($this->colorName->color)) return false;
    // Check all fields are set
    foreach(["language", "name"] as $key) {
      if (!isset($this->colorName->$key) || is_null($this->colorName->$key) || empty($this->colorName->$key)) return false;
    }
    // Validate language
    $em = DB::getEM();
    foreach($this->colorName->color->names as $other) {
      if ($other->name != $this->colorName->name) continue;
      if (is_null($this->colorName->id)) return false;
      if ($other->id != $this->colorName->id) return false;
    }
    return true;
  }

  public function validatePersist() {
    $ok = $this->validate();
    if (!$ok) return null;
    $em = DB::getEM();
    $em->persist($this->colorName);
    $em->flush();
    return $this->colorName;
  }
  
}