<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
	
	
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - EmePictures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon_io/EME_VERTICAL.ico" type="image/x-icon">
</head>
<body>

<!-- Banner de imagen -->
<div class="text-center">
    <img src="images/baner_prueba.jpg" alt="Banner EmePictures" class="img-fluid w-100">
</div>


<nav class="navbar navbar-expand-lg navbar-dark px-3" style="background-color: #2185d0;">


    <a class="navbar-brand" href="videos.php">EmePictures</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
           

            <li class="nav-item"><a class="nav-link" href="videos.php">Videos</a></li>
            
            <li class="nav-item"><a class="nav-link" href="./subir_video.php">Subir video</a></li>

		</ul>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</nav>
<div class="container py-4">
