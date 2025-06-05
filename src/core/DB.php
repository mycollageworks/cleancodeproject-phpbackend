<?php

namespace Core;

use PDO;
use PDOException;

class DB
{
    private static ?DB $instance = null;

    protected PDO $pdo;

    private function __construct(array $config)
    {
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            exit('Database connection failed: '.$e->getMessage());
        }
    }

    public static function getInstance(array $config = []): self
    {
        if (self::$instance === null) {
            if (empty($config)) {
                exit('DB is not initialized. Pass config on first getInstance() call.');
            }
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = []): bool|\PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetch(string $sql, array $params = []): array|false
    {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert(string $sql, array $params = []): int
    {
        $this->query($sql, $params);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }

    public function delete(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return 0; // No rows deleted
        }

        return $stmt->rowCount();
    }

    public function raw(): PDO
    {
        return $this->pdo;
    }
}
