<?php

class Conexion
{
    private static ?PDO $conexion = null;

    public static function obtener(): PDO
    {
        if (self::$conexion === null) {

            $host = 'localhost';
            $db = 'sportzone';
            $usuario = 'root';
            $clave = '';

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            try {

                self::$conexion = new PDO(
                    $dsn,
                    $usuario,
                    $clave,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

            } catch (PDOException $e) {

                die(
                    "Error de conexión con la base de datos: "
                    . $e->getMessage()
                );
            }
        }

        return self::$conexion;
    }
}
