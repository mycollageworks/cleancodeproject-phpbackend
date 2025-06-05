<?php

namespace Core;


class Response
{
  /**
   * Send a JSON response with the given data and status code.
   *
   * @param array $data
   * @param int $statusCode
   */
  public static function json(array $data, int $statusCode = 200): void
  {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
  }

  /**
   * Send a success response with a message.
   *
   * @param string $message
   * @param int $statusCode
   */
  public static function success(string $message, int $statusCode = 200): void
  {
    self::json(['message' => $message], $statusCode);
  }

  /**
   * Send an error response with a message.
   *
   * @param string $message
   * @param int $statusCode
   */
  public static function error(string $message, int $statusCode = 400): void
  {
    self::json(['error' => $message], $statusCode);
  }
}
