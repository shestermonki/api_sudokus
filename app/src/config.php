<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Fixem la sortida d'errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definim constants útils
define('APP_FOLDER',  dirname(__DIR__));
define('LOG_FOLDER', APP_FOLDER . '/logs');

// Executem la petició amb Slim
\ApiSudoku\ApiSudoku::processRequest();
