<?php

namespace Core;

use Dotenv\Dotenv;

/**
 * Bootstrap class for the application.
 * This class is responsible for initializing the application and its components.
 * It can be extended to add more functionality as needed.
 */
class Application
{
    /**
     * Initializes the application.
     * This method can be used to set up the application environment, load configurations, etc.
     */
    public function init(): void
    {
        // Load environment variables
        Dotenv::createImmutable(__DIR__.'/../..')->load();

        // Load database configuration
        $this->loadDB();

        $this->loadRouter();
    }

    /**
     * Returns the router instance.
     * This method can be used to access the router instance for adding routes or handling requests.
     *
     * @return Router
     */
    public function loadRouter(): void
    {
        $router = new Router;
        // Load the application configuration
        require_once __DIR__.'/../routes/api.php';

        $router->dispatch();
    }

    /**
     * Loads the database connection.
     * This method initializes the database connection using the configuration provided in the environment variables.
     * It uses the DB class to establish the connection.
     */
    private function loadDB(): void
    {
        DB::getInstance([
            'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_NAME'] ?? 'note_manager',
            'username' => $_ENV['DB_USER'] ?? 'root',
            'password' => $_ENV['DB_PASS'] ?? '',
            'charset' => 'utf8mb4',
        ]);
    }
}
