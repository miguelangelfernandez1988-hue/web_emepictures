<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'includes/header.php';
require 'includes/db.php';

$mensaje = '';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['directorio_video1'])) {
        $mensaje .= "No se recibió el directorio destino.<br>";
    } else {
        $directorio = $_POST['directorio_video1'];
        $mensaje .= "Directorio recibido: $directorio <br>";
    }
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_FILES['video1'], $_FILES['thumbnail']) &&
    isset($_POST['directorio_video1'])
) {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    // Procesar miniatura
    $miniatura = $_FILES['thumbnail'];
    $rutaMiniaturaWeb = '';

    $permitidasImagen = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (in_array($miniatura['type'], $permitidasImagen) && $miniatura['size'] <= 20 * 1024 * 1024) {
        $nombreMiniatura = time() . '_' . basename($miniatura['name']);
        $rutaFisicaMiniatura = __DIR__ . '/uploads/miniaturas/' . $nombreMiniatura;
        $rutaMiniaturaWeb = 'uploads/miniaturas/' . $nombreMiniatura;

        if (!is_dir(dirname($rutaFisicaMiniatura))) {
            mkdir(dirname($rutaFisicaMiniatura), 0777, true);
        }

        if (!move_uploaded_file($miniatura['tmp_name'], $rutaFisicaMiniatura)) {
            $mensaje .= "Error al subir la miniatura.<br>";
            $rutaMiniaturaWeb = '';
        }
    } else {
        $mensaje .= "Miniatura inválida o demasiado grande (máx 20MB).<br>";
    }

    // Procesar video
    $video = $_FILES['video1'];
    $directorio = $_POST['directorio_video1']; // 'portada' o 'galeria'

    if (!in_array($directorio, ['portada', 'galeria'])) {
        $mensaje .= "Directorio no válido.<br>";
    } else {
        $subcarpeta = $directorio; // ya es 'portada' o 'galeria'
        $permitidos = ['video/mp4', 'video/quicktime', 'video/x-matroska'];

        if (!in_array($video['type'], $permitidos)) {
            $mensaje .= "Formato de video no permitido.<br>";
        } elseif ($video['size'] > 500 * 1024 * 1024) {
            $mensaje .= "El archivo de video es demasiado grande.<br>";
        } else {
            $nombreArchivo = time() . '_' . basename($video['name']);

            // Ruta física del servidor
            $rutaFisica = __DIR__ . "/uploads/$subcarpeta/" . $nombreArchivo;
			

            // Ruta relativa web
            $rutaWeb = "uploads/$subcarpeta/" . $nombreArchivo;

            if (!is_dir(dirname($rutaFisica))) {
                mkdir(dirname($rutaFisica), 0777, true);
            }

            if (move_uploaded_file($video['tmp_name'], $rutaFisica)) {
                $stmt = $pdo->prepare("INSERT INTO videos (titulo, descripcion, ruta, miniatura, fecha_subida) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$titulo, $descripcion, $rutaWeb, $rutaMiniaturaWeb]);
                $mensaje .= "Video subido correctamente a /$subcarpeta.<br>";
            } else {
                $mensaje .= "Error al mover el archivo de video al servidor.<br>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Video - CRM TBP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">Subir nuevo video</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form action="subir_video.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título del video</label>
            <input type="text" class="form-control" name="titulo" id="titulo" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción (opcional)</label>
            <textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Miniatura del video</label>
            <input type="file" class="form-control" name="thumbnail" id="thumbnail" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="video1" class="form-label">Archivo de video</label>
            <input type="file" class="form-control" name="video1" id="video1" accept="video/*" required>
            <label for="directorio_video1" class="form-label mt-2">Directorio destino</label>
            <select name="directorio_video1" id="directorio_video1" class="form-select" required>
                <option value="portada">Portada</option>
                <option value="galeria">Galería</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Subir video</button>
    </form>
</div>

</body>
</html>
