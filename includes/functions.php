<?php
function obtenerSocios($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM socios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener socios: " . $e->getMessage());
        return [];
    }
}


function obtenerPagos($pdo) {
    try {
        $stmt = $pdo->query("SELECT pagos.*, socios.nombre, socios.estado FROM pagos JOIN socios ON pagos.socio_id = socios.id ORDER BY fecha_pago DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obteniendo pagos: " . $e->getMessage());
        return [];
    }
}



