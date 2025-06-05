<?php

namespace Core;


class Controller
{
  // json response
  protected function jsonResponse(array $data, int $statusCode = 200): void
  {
    Response::json($data, $statusCode);
  }

  // success response
  protected function successResponse(string $message, int $statusCode = 200): void
  {
    $this->jsonResponse(['message' => $message], $statusCode);
  }

  // error response
  protected function errorResponse(string $message, int $statusCode = 400): void
  {
    $this->jsonResponse(['error' => $message], $statusCode);
  }

  // bad request response
  protected function badRequestResponse(string $message): void
  {
    $this->errorResponse($message, 400);
  }
}
