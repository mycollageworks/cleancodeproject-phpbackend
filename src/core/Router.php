<?php

namespace Core;

use InvalidArgumentException;
use ReflectionMethod;
use ReflectionNamedType;

class Router
{
  private array $routes = [];

  // Method
  public function request(string $method, string $path, callable|array $handler)
  {
    if (!isset($this->routes[$method])) {
      $this->routes[$method] = [];
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
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestPath = str_replace('/index.php', '', $requestPath); // if using built-in server

    $handler = $this->routes[$method][$requestPath] ?? null;


    $routes = $this->routes[$method] ?? [];

    foreach ($routes as $routePath => $handler) {
      $pattern = preg_replace('#\{(\w+)\}#', '(?P<\1>[^/]+)', $routePath);
      $pattern = "#^" . $pattern . "$#";

      if (preg_match($pattern, $requestPath, $matches)) {
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        // Build real callable with parameter injection
        if (!is_callable($handler)) {
          $className = $handler[0];
          $methodName = $handler[1] ?? 'index';

          if (!class_exists($className) || !method_exists($className, $methodName)) {
            throw new InvalidArgumentException("Handler {$className}::{$methodName} is not callable.");
          }

          $handler = function () use ($className, $methodName, $params) {
            $method = new ReflectionMethod($className, $methodName);
            $args = [];

            foreach ($method->getParameters() as $param) {
              $type = $param->getType();

              if ($type && $type instanceof ReflectionNamedType) {
                $name = $param->getName();
                $typeName = $type->getName();

                if ($typeName === \Core\Request::class) {
                  $args[] = new \Core\Request();
                } elseif (isset($params[$name])) {
                  // Cast to expected type (e.g. int)
                  settype($params[$name], $typeName);
                  $args[] = $params[$name];
                } else {
                  $args[] = null;
                }
              } else {
                $args[] = $params[$param->getName()] ?? null;
              }
            }

            return $method->invokeArgs(new $className, $args);
          };
        }

        echo $handler();
        return;
      }
    }

    Response::json(['error' => 'Not Found'], 404);
  }
};
