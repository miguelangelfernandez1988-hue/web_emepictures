<?php
require 'includes/header.php';
require 'includes/db.php';
require 'includes/functions.php';

$socios = obtenerSocios($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<link rel="icon" href="favicon_io/EME_VERTICAL.ico" type="image/x-icon">

  <meta charset="UTF-8">
  <title>Galería de Videos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
  body {
    background-color: #000;
    color: #fff;
    margin: 0;
    padding: 20px;
    font-family: Arial, sans-serif;
  }

  h1 {
    text-align: center;
    margin-bottom: 30px;
  }

  .gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
  }

  .video-card {
    width: 300px;
    cursor: pointer;
    border: 2px solid #fff;
    border-radius: 10px;
    overflow: hidden;
    background-color: #111;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s;
  }

  .video-thumb {
    width: 100%;
    overflow: hidden;
  }

  .video-thumb video {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.4s ease;
  }

  .video-thumb:hover video {
    transform: scale(1.05);
  }

  .video-title {
    padding: 10px;
    text-align: center;
    font-weight: bold;
  }

  .modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.9);
    justify-content: center;
    align-items: center;
    padding: 20px;
  }

  .modal-content {
    position: relative;
    width: 90%;
    max-width: 800px;
    background-color: #111;
    padding: 20px;
    border-radius: 10px;
  }

  .modal-content video {
    width: 100%;
    height: auto;
    border: 2px solid #fff;
    border-radius: 10px;
    margin-bottom: 15px;
  }

  .close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 30px;
    color: #fff;
    cursor: pointer;
  }

  .video-info h2 {
    margin: 10px 0 5px;
  }

  .video-info p {
    margin: 5px 0;
  }

  #relatedVideos {
    margin-top: 30px;
  }

  #relatedVideos h3 {
    text-align: center;
    margin-bottom: 15px;
  }

  #relatedGallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
  }

  #relatedGallery .video-card {
    width: 150px;
  }

  #relatedGallery .video-title {
    font-size: 14px;
  }

  </style>

  <script>
    //document.addEventListener('contextmenu', event => event.preventDefault());

    function openModal(src, title, description, views) {
      const modal = document.getElementById('videoModal');
      const modalVideo = document.getElementById('modalVideo');
      const titleEl = document.getElementById('videoTitle');
      const descEl = document.getElementById('videoDescription');
      const viewsEl = document.getElementById('videoViews');

      modalVideo.src = src;
      titleEl.textContent = title;
      descEl.textContent = description;
      viewsEl.textContent = views + ' visualizaciones';

      renderRelatedVideos(src);

      modal.style.display = 'flex';
      modalVideo.play();
    }

    function closeModal() {
      const modal = document.getElementById('videoModal');
      const modalVideo = document.getElementById('modalVideo');
      modal.style.display = 'none';
      modalVideo.pause();
      modalVideo.currentTime = 0;
    }

    window.addEventListener('click', function(event) {
      const modal = document.getElementById('videoModal');
      if (event.target == modal) {
        closeModal();
      }
    });

    function setupVideoClicks() {
      const cards = document.querySelectorAll('.video-card');
      cards.forEach(card => {
        card.addEventListener('click', function() {
          const videoSrc = this.getAttribute('data-src');
          const title = this.getAttribute('data-title');
          const description = this.getAttribute('data-description');
          const views = this.getAttribute('data-views');
          openModal(videoSrc, title, description, views);
        });
      });
    }

    function renderRelatedVideos(currentSrc) {
      const relatedContainer = document.getElementById('relatedGallery');
      relatedContainer.innerHTML = '';

      const others = window.allVideos.filter(v => v.src !== currentSrc);

      others.forEach(video => {
        const card = document.createElement('div');
        card.classList.add('video-card');
        card.setAttribute('data-src', video.src);
        card.setAttribute('data-title', video.title);
        card.setAttribute('data-description', video.description);
        card.setAttribute('data-views', video.views);

        card.innerHTML = `
          <div class="video-thumb">
            <video muted>
              <source src="${video.src}" type="video/mp4">
            </video>
          </div>
          <div class="video-title">${video.title}</div>
        `;

        card.addEventListener('click', function() {
          openModal(video.src, video.title, video.description, video.views);
        });

        relatedContainer.appendChild(card);
      });
    }

    window.onload = setupVideoClicks;
  </script>
</head>
<body>

  <h1>Galería de Videos</h1>

<div class="gallery">
  <?php
    $stmt = $pdo->query("SELECT titulo, descripcion, ruta, miniatura FROM videos ORDER BY fecha_subida DESC");
    $videosJS = []; // Array para pasar a JavaScript
    while ($video = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $titulo = htmlspecialchars($video['titulo']);
        $descripcion = htmlspecialchars($video['descripcion']);
        $ruta = htmlspecialchars($video['ruta']);
        $miniatura = htmlspecialchars($video['miniatura']);
        $vistas = rand(50, 500);


    // Filtrar solo rutas que comiencen con /uploads/portada/
    if (strpos($ruta, 'uploads/galeria/') !== 0) {
        continue;
    }
	
        $videosJS[] = [
            'title' => $titulo,
            'description' => $descripcion,
            'src' => $ruta,
            'thumbnail' => $miniatura,
            'views' => $vistas
        ];

        echo '
        <div class="video-card"
            data-src="' . $ruta . '"
            data-title="' . $titulo . '"
            data-description="' . $descripcion . '"
            data-views="' . $vistas . '">
            <div class="video-thumb">
                <img src="' . $miniatura . '" alt="Miniatura de ' . $titulo . '">
            </div>
            <div class="video-title">' . $titulo . '</div>
        </div>';
    }
  ?>
</div>


  <!-- Modal -->
  <div id="videoModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <video id="modalVideo" controls controlsList="nodownload" oncontextmenu="return false;">
        <source src="" type="video/mp4">
        Tu navegador no soporta la reproducción de video.
      </video>
      <div class="video-info">
        <h2 id="videoTitle"></h2>
        <p id="videoDescription"></p>
        <p id="videoViews"></p>
      </div>

      <div id="relatedVideos">
        <h3>Otros videos</h3>
        <div id="relatedGallery"></div>
      </div>
    </div>
  </div>

  <script>
    window.allVideos = <?php echo json_encode($videosJS, JSON_UNESCAPED_UNICODE); ?>;
  </script>

</body>
</html>
