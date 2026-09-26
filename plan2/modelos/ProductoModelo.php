<?php

class ProductoModelo
{
    // Lista blanca: solo estas columnas pueden usarse para ordenar (nunca se ordena por lo que llega crudo del usuario)
    private const COLUMNAS_ORDEN = ['nombre', 'categoria', 'precio', 'stock', 'creado_en'];
    private const POR_PAGINA = 10;

    public static function listar(string $busqueda, string $columna, string $direccion, int $pagina): array
    {
        $pdo = Conexion::obtener();

        $columna = in_array($columna, self::COLUMNAS_ORDEN, true) ? $columna : 'nombre';
        $direccion = strtoupper($direccion) === 'DESC' ? 'DESC' : 'ASC';
        $pagina = max(1, $pagina);
        $offset = self::POR_PAGINA * ($pagina - 1);

        $condicion = 'WHERE activo = 1';
        $parametros = [];

        if ($busqueda !== '') {
            $condicion .= ' AND (nombre LIKE :busqueda1 OR categoria LIKE :busqueda2)';
            $parametros['busqueda1'] = "%$busqueda%";
            $parametros['busqueda2'] = "%$busqueda%";
        }

        $totalConsulta = $pdo->prepare("SELECT COUNT(*) AS total FROM productos $condicion");
        $totalConsulta->execute($parametros);
        $total = (int) $totalConsulta->fetch()['total'];

        // $columna y $direccion ya pasaron por la lista blanca arriba, por eso es seguro concatenarlas aquí
        // id_producto AS id: la tabla real usa id_producto como llave primaria; el alias evita tocar el resto del código
        $sql = "SELECT *, id_producto AS id FROM productos $condicion ORDER BY $columna $direccion LIMIT :limite OFFSET :offset";
        $consulta = $pdo->prepare($sql);
        foreach ($parametros as $clave => $valor) {
            $consulta->bindValue(":$clave", $valor);
        }
        $consulta->bindValue(':limite', self::POR_PAGINA, PDO::PARAM_INT);
        $consulta->bindValue(':offset', $offset, PDO::PARAM_INT);
        $consulta->execute();

        return [
            'productos' => $consulta->fetchAll(),
            'total' => $total,
            'totalPaginas' => (int) ceil($total / self::POR_PAGINA),
            'paginaActual' => $pagina,
            'columna' => $columna,
            'direccion' => $direccion,
        ];
    }

    public static function obtener(int $id): ?array
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare('SELECT *, id_producto AS id FROM productos WHERE id_producto = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    public static function crear(array $datos): int
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare(
            'INSERT INTO productos (nombre, categoria, precio, stock, activo)
             VALUES (:nombre, :categoria, :precio, :stock, 1)'
        );
        $consulta->execute($datos);
        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $id, array $datos): void
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare(
            'UPDATE productos SET nombre = :nombre, categoria = :categoria, precio = :precio, stock = :stock
             WHERE id_producto = :id'
        );
        $consulta->execute($datos + ['id' => $id]);
    }

    public static function desactivar(int $id): void
    {
        // Borrado LÓGICO: nunca DELETE, solo se marca activo = 0
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare('UPDATE productos SET activo = 0 WHERE id_producto = :id');
        $consulta->execute(['id' => $id]);
    }

    public static function descontarStock(PDO $pdo, int $id, int $cantidad): bool
    {
        $consulta = $pdo->prepare(
            'UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id AND stock >= :cantidad'
        );
        $consulta->execute(['cantidad' => $cantidad, 'id' => $id]);
        return $consulta->rowCount() === 1;
    }
}