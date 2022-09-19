<?php
namespace ApiSudoku\Model;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DB {
  private static $entityManager = null;

  public static function getEM() {
    if (is_null(static::$entityManager)) {
      // Initialize the entity-manager
      // Create a simple "default" Doctrine ORM configuration for Annotations
      $isDevMode = true;
      $proxyDir = null;
      $cache = null;
      $useSimpleAnnotationReader = false;
      $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
      // database configuration parameters
      // FORMAT "mysql://user:password@host/dbname"
      $conn = array(
        'url' => 'mysql://sudokuuser:sudokupassword@api_sudoku_db/sudokudb',
      );
      // obtaining the entity manager
      static::$entityManager = EntityManager::create($conn, $config);
    }
    return static::$entityManager;
  }
}