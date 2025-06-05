<?php

namespace Core;


class Request
{
  private array $queryParams = [];
  private array $postParams = [];
  private array $headers = [];

  public function __construct()
  {
    $this->queryParams = $_GET;
    $this->postParams = $_POST;
    $this->headers = getallheaders();
  }

  public function getQueryParam(string $key, mixed $default = null): mixed
  {
    return $this->queryParams[$key] ?? $default;
  }

  public function getPostParam(string $key, mixed $default = null): mixed
  {
    return $this->postParams[$key] ?? $default;
  }

  public function getHeader(string $key, mixed $default = null): mixed
  {
    return $this->headers[$key] ?? $default;
  }
}
