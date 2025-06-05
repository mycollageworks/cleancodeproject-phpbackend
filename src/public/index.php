<?php

use Core\Response;

error_reporting(E_ALL & ~E_WARNING); // hilangkan WARNING tapi tetap tampilkan error penting

require_once __DIR__ . '/../../vendor/autoload.php';


// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Handle Exception → JSON response
set_exception_handler(function (Throwable $e) {
    // Log the exception (optional)
    Response::json([
        'error' => 'Internal Server Error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ], 500);
});

// Handle Error (like undefined array keys)
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
    if (! (error_reporting() & $errno)) {
        return;
    }

    Response::json([
        'error' => 'Runtime Error',
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline,
    ], 500);
});

// helper form debugging
if (! function_exists('dd')) {
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

$app = new Core\Application;
$app->init();
