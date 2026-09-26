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

            self::$conexion = new PDO(
                $dsn,
                $usuario,
                $clave,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$conexion;
    }
}