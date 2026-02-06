<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Clase Database - Manejo de conexión a base de datos
 * Patrón Singleton
 */
class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct()
    {
        // Constructor privado para Singleton
    }

    /**
     * Obtener instancia única de Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtener conexión PDO
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Establecer conexión a la base de datos
     */
    private function connect(): void
    {
        try {
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            throw new \RuntimeException("Error de conexión a base de datos: " . $e->getMessage());
        }
    }

    /**
     * Cerrar conexión
     */
    public function disconnect(): void
    {
        $this->connection = null;
    }

    /**
     * Registrar errores en log
     */
    private function logError(string $message): void
    {
        $logFile = LOGS_PATH . '/database_errors.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        if (!is_dir(LOGS_PATH)) {
            mkdir(LOGS_PATH, 0777, true);
        }
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Prevenir clonación
     */
    private function __clone()
    {
    }

    /**
     * Prevenir deserialización
     */
    public function __wakeup()
    {
        throw new \Exception("No se puede deserializar un Singleton");
    }
}
