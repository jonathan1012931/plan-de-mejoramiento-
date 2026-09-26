<?php

class ClienteModelo
{
    private const COLUMNAS_ORDEN = ['nombre', 'documento', 'correo', 'creado_en'];
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
            $condicion .= ' AND (nombre LIKE :busqueda1 OR correo LIKE :busqueda2 OR documento LIKE :busqueda3)';
            $parametros['busqueda1'] = "%$busqueda%";
            $parametros['busqueda2'] = "%$busqueda%";
            $parametros['busqueda3'] = "%$busqueda%";
        }

        $totalConsulta = $pdo->prepare("SELECT COUNT(*) AS total FROM clientes $condicion");
        $totalConsulta->execute($parametros);
        $total = (int) $totalConsulta->fetch()['total'];

        $sql = "SELECT * FROM clientes $condicion ORDER BY $columna $direccion LIMIT :limite OFFSET :offset";
        $consulta = $pdo->prepare($sql);
        foreach ($parametros as $clave => $valor) {
            $consulta->bindValue(":$clave", $valor);
        }
        $consulta->bindValue(':limite', self::POR_PAGINA, PDO::PARAM_INT);
        $consulta->bindValue(':offset', $offset, PDO::PARAM_INT);
        $consulta->execute();

        return [
            'clientes' => $consulta->fetchAll(),
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
        $consulta = $pdo->prepare('SELECT * FROM clientes WHERE id = :id');
        $consulta->execute(['id' => $id]);
        return $consulta->fetch() ?: null;
    }

    public static function correoExiste(string $correo, ?int $idExcluir = null): bool
    {
        $pdo = Conexion::obtener();
        $sql = 'SELECT id FROM clientes WHERE correo = :correo';
        $parametros = ['correo' => $correo];
        if ($idExcluir !== null) {
            $sql .= ' AND id != :id';
            $parametros['id'] = $idExcluir;
        }
        $consulta = $pdo->prepare($sql);
        $consulta->execute($parametros);
        return (bool) $consulta->fetch();
    }

    public static function documentoExiste(string $documento, ?int $idExcluir = null): bool
    {
        $pdo = Conexion::obtener();
        $sql = 'SELECT id FROM clientes WHERE documento = :documento';
        $parametros = ['documento' => $documento];
        if ($idExcluir !== null) {
            $sql .= ' AND id != :id';
            $parametros['id'] = $idExcluir;
        }
        $consulta = $pdo->prepare($sql);
        $consulta->execute($parametros);
        return (bool) $consulta->fetch();
    }

    public static function crear(array $datos): int
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare(
            'INSERT INTO clientes (nombre, documento, correo, telefono, activo)
             VALUES (:nombre, :documento, :correo, :telefono, 1)'
        );
        $consulta->execute($datos);
        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $id, array $datos): void
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare(
            'UPDATE clientes SET nombre = :nombre, documento = :documento, correo = :correo, telefono = :telefono
             WHERE id = :id'
        );
        $consulta->execute($datos + ['id' => $id]);
    }

    public static function desactivar(int $id): void
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare('UPDATE clientes SET activo = 0 WHERE id = :id');
        $consulta->execute(['id' => $id]);
    }
}