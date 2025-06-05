<?php

namespace Core;


class Request
{
  private array $queryParams = [];
  private array $postParams = [];
  private array $headers = [];

  private array $server = [];
  private array $body = [];

  public function __construct()
  {
    $this->queryParams = $_GET;
    $this->postParams = $_POST;
    $this->headers = getallheaders();
    $this->server = $_SERVER;
    $this->body   = json_decode(file_get_contents('php://input'), true) ?? [];
  }

  public function getQueryParam(string $key, mixed $default = null): mixed
  {
    return $this->queryParams[$key] ?? $default;
  }

  public function getPostParam(string $key, mixed $default = null): mixed
  {
    return $this->body[$key] ?? $this->postParams[$key] ?? $default;
  }

  public function getHeader(string $key, mixed $default = null): mixed
  {
    return $this->headers[$key] ?? $default;
  }
}
