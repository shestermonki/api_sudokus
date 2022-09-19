<?php
namespace ApiSudoku\Model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="colors")
 */
class Color implements \JsonSerializable {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  public $id;

  /** @ORM\Column(type="string") */
  public $name;

  /** @ORM\Column(type="integer") */
  public $red;
  
  /** @ORM\Column(type="integer") */
  public $green;

  /** @ORM\Column(type="integer") */
  public $blue;

  /**
   * One color has many names. This is the inverse side.
   * @ORM\OneToMany(targetEntity="ColorName", mappedBy="color")
   */
  public $names;

  public function __construct() {
    $this->names = new ArrayCollection();
  }
  
  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'red' => $this->red,
      'green' => $this->green,
      'blue' => $this->blue,
      'names' => $this->names->toArray(),
    ];
  }
}