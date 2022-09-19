<?php
namespace ApiSudoku\Model;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="color_names")
 */
class ColorName implements \JsonSerializable {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  public $id;

  /**
   * @ORM\Column(type="string")
   */
  public $language;

  /**
   * @ORM\Column(type="string")
   */
  public $name;

  /**
   * Many color names have one color. This is the owning side.
   * @ORM\ManyToOne(targetEntity="Color", inversedBy="names")
   * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
   */
  public $color;

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'language' => $this->language,
      'name' => $this->name,
    ];
  }

}