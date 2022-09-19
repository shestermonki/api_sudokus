<?php
namespace ApiSudoku\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="players")
 */
class Player implements \JsonSerializable {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  public ?int $id;
  /** @ORM\Column(type="string") */
  public string $username;
  /** @ORM\Column(type="string") */
  public string $password;
  /** @ORM\Column(type="string") */
  public string $email;
  /** @ORM\Column(type="string") */
  public string $token;

  public function createNewToken() : string {
    $bytes = openssl_random_pseudo_bytes(40);
    $token = bin2hex($bytes);
    $this->token = $token;
    return $token;
  }

  // $length must be an even integer greater or equal to 4
  public static function randomPassword(int $length = 10) {
      $chars1 = "abcdefghijklmnopqrstuvwxyz";
      $chars2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $chars3 = "0123456789";
      $chars4 = "!$%&/+-_.:*";
      $password = substr(str_shuffle($chars1), 0, $length / 2 - 1);
      $password.= substr(str_shuffle($chars2), 0, $length / 2 - 1);
      $password.= substr(str_shuffle($chars3), 0, 1);
      $password.= substr(str_shuffle($chars4), 0, 1);
      $password = str_shuffle($password);
      return $password;
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'username' => $this->username,
      'password' => $this->password,
      'email' => $this->email,
      'token' => $this->token,
    ];
  }
}