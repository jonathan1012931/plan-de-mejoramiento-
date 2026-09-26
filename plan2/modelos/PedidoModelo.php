<?php

class PedidoModelo
{
    /**
     * @param array<int, array{producto_id:int, cantidad:int}> $lineas
     * @throws Exception si no hay stock suficiente o algo falla (dispara rollBack)
     */
    public static function crearConDetalle(int $clienteId, array $lineas): int
    {
        $pdo = Conexion::obtener();
        $pdo->beginTransaction();

        try {
            $insertarPedido = $pdo->prepare('INSERT INTO pedidos (cliente_id, total) VALUES (:cliente_id, 0)');
            $insertarPedido->execute(['cliente_id' => $clienteId]);
            $pedidoId = (int) $pdo->lastInsertId();

            $insertarDetalle = $pdo->prepare(
                'INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario)
                 VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)'
            );
            $obtenerProducto = $pdo->prepare('SELECT precio, stock FROM productos WHERE id_producto = :id FOR UPDATE');

            $total = 0;

            foreach ($lineas as $linea) {
                $obtenerProducto->execute(['id' => $linea['producto_id']]);
                $producto = $obtenerProducto->fetch();

                if (!$producto) {
                    throw new Exception('Uno de los productos ya no existe.');
                }
                if ($producto['stock'] < $linea['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto seleccionado.');
                }

                $descontado = ProductoModelo::descontarStock($pdo, $linea['producto_id'], $linea['cantidad']);
                if (!$descontado) {
                    throw new Exception('No se pudo descontar el stock, intenta de nuevo.');
                }

                $insertarDetalle->execute([
                    'pedido_id' => $pedidoId,
                    'producto_id' => $linea['producto_id'],
                    'cantidad' => $linea['cantidad'],
                    'precio_unitario' => $producto['precio'],
                ]);

                $total += $producto['precio'] * $linea['cantidad'];
            }

            $pdo->prepare('UPDATE pedidos SET total = :total WHERE id = :id')
                ->execute(['total' => $total, 'id' => $pedidoId]);

            $pdo->commit();
            return $pedidoId;

        } catch (Exception $e) {
            $pdo->rollBack(); // nada de lo que se hizo arriba queda guardado
            throw $e;
        }
    }

    public static function listar(): array
    {
        $pdo = Conexion::obtener();
        return $pdo->query(
            'SELECT p.id, p.total, p.creado_en, c.nombre AS cliente_nombre
             FROM pedidos p INNER JOIN clientes c ON c.id = p.cliente_id
             ORDER BY p.creado_en DESC'
        )->fetchAll();
    }

    public static function obtenerDetalle(int $pedidoId): array
    {
        $pdo = Conexion::obtener();
        $consulta = $pdo->prepare(
            'SELECT d.cantidad, d.precio_unitario, pr.nombre AS producto_nombre, pr.activo AS producto_activo
             FROM detalle_pedidos d INNER JOIN productos pr ON pr.id_producto = d.producto_id
             WHERE d.pedido_id = :pedido_id'
        );
        $consulta->execute(['pedido_id' => $pedidoId]);
        return $consulta->fetchAll();
    }
}