<?php

use Core\Router;

require_once __DIR__ . '/../../vendor/autoload.php';

// helper form debugging
if (!function_exists('dd')) {
  function dd(...$args): void
  {
    foreach ($args as $arg) {
      echo '<pre>';
      var_dump($arg);
      echo '</pre>';
    }
    exit;
  }
}

$app = new Core\Application();
$app->init();
