<?php

namespace Core;

use InvalidArgumentException;

class Router
{
  private array $routes = [];

  // function run
  public function run()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('/index.php', '', $path); // if using built-in server

    if (isset($this->routes[$method][$path])) {
      header('Content-Type: application/json');
      echo ($this->routes[$method][$path])();
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not Found']);
    }
  }


  // Method
  public function request(string $method, string $path, callable|array $handler)
  {
    if (!isset($this->routes[$method])) {
      $this->routes[$method] = [];
    }

    // reflect the handler to ensure it's callable
    if (!is_callable($handler)) {
      $className = $handler[0];
      $methodName = $handler[1] ?? 'index';

      if (!class_exists($className) || !method_exists($className, $methodName)) {
        throw new InvalidArgumentException("Handler {$className}::{$methodName} is not callable.");
      }

      $handler = function () use ($className, $methodName) {
        $instance = new $className();
        return $instance->$methodName();
      };
    }

    $this->routes[$method][$path] = $handler;
  }
  public function any(string $path, callable|array $handler)
  {
    $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD', 'TRACE', 'CONNECT'];
    foreach ($methods as $method) {
      $this->request($method, $path, $handler);
    }
  }
  public function get(string $path, callable|array $handler)
  {
    $this->request('GET', $path, $handler);
  }
  public function post(string $path, callable|array $handler)
  {
    $this->request('POST', $path, $handler);
  }
  public function put(string $path, callable|array $handler)
  {
    $this->request('PUT', $path, $handler);
  }
  public function delete(string $path, callable|array $handler)
  {
    $this->request('DELETE', $path, $handler);
  }
  public function patch(string $path, callable|array $handler)
  {
    $this->request('PATCH', $path, $handler);
  }
  public function options(string $path, callable|array $handler)
  {
    $this->request('OPTIONS', $path, $handler);
  }
  public function head(string $path, callable|array $handler)
  {
    $this->request('HEAD', $path, $handler);
  }
  public function trace(string $path, callable|array $handler)
  {
    $this->request('TRACE', $path, $handler);
  }
  public function connect(string $path, callable|array $handler)
  {
    $this->request('CONNECT', $path, $handler);
  }


  public function dispatch()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('/index.php', '', $path); // if using built-in server

    $handler = $this->routes[$method][$path] ?? null;

    if ($handler) {
      header('Content-Type: application/json');
      echo $handler();
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not Found']);
    }
  }
};
