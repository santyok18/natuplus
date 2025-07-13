<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['ID_USUARIO'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No estás logueado.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$id_usuario = $_SESSION['ID_USUARIO'];

if (!$data || count($data) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Carrito vacío.']);
    exit();
}

try {
    foreach ($data as $item) {
        $id_producto = intval($item['id']);
        $cantidad = intval($item['cantidad']);

        // 1. Insertar en PEDIDOS
        $stmt = $conn->prepare("INSERT INTO pedidos (cant_pedido, fecha_pedido, fk_id_producto) VALUES (?, NOW(), ?)");
        $stmt->bind_param("ii", $cantidad, $id_producto);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;
        $stmt->close();

        // 2. Insertar en FACTURACION
        $descripcion = "Compra de producto ID $id_producto - Cantidad: $cantidad";
        $stmt = $conn->prepare("INSERT INTO facturacion (fecha, descripcion, fk_id_usuario, fk_id_pedido) VALUES (NOW(), ?, ?, ?)");
        $stmt->bind_param("sii", $descripcion, $id_usuario, $id_pedido);
        $stmt->execute();
        $stmt->close();

        // 3. Actualizar STOCK
        $stmt = $conn->prepare("UPDATE producto SET stock = stock - ? WHERE id_producto = ? AND stock >= ?");
        $stmt->bind_param("iii", $cantidad, $id_producto, $cantidad);
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la compra']);
}
?>
