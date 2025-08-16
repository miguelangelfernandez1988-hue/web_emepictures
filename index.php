<?php
/*
$ip_autorizada = '87.221.175.187';
$ip_usuario = $_SERVER['REMOTE_ADDR'];

if ($ip_usuario !== $ip_autorizada) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head><meta charset='UTF-8'><title>Página en construcción</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding-top: 100px; color: #333; }
    </style>
    </head>
    <body>
        <h1>🚧 Página en construcción</h1>
        <p>Estamos trabajando en ella. Vuelve pronto.</p>
    </body></html>";
    exit();
}
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'includes/db.php';
require 'includes/auth.php';
$_SESSION['ultimo_acceso'] = time();

if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];
        if (login($usuario, $clave)) {
            $_SESSION['usuario'] = $usuario;
            header('Location: videos.php');
            exit;
        } else {
            $error = 'Usuario o clave incorrectos';
        }
    }

    if (isset($_POST['registro'])) {
        $nuevoUsuario = trim($_POST['nuevo_usuario']);
        $nuevaClave = $_POST['nueva_clave'];
        if (strlen($nuevoUsuario) < 3 || strlen($nuevaClave) < 4) {
            $error = 'El usuario debe tener al menos 3 caracteres y la clave al menos 4.';
        } elseif (registrar($nuevoUsuario, $nuevaClave)) {
            $exito = 'Usuario creado correctamente. Ya puedes iniciar sesión.';
        } else {
            $error = 'Error al registrar. Puede que el usuario ya exista.';
        }
    }
}
?>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$directorio = 'uploads/carrusel/';
$imagenes = glob($directorio . "*.{jpg,jpeg,png,gif,webp,JPG}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EmePictures - Videos exclusivos y casting para creadores</title>
<meta name="description" content="EmePictures: plataforma de videos exclusivos, casting de actrices y servicios para creadores de contenido.">

    <link rel="icon" href="favicon_io/EME_VERTICAL.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="http://www.emepictures.com/css/estilos.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}
.blinking-button {
  animation: blink 1s infinite;
}
</style>

<style>
#loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7); /* Negro con 70% opacidad */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999;
  transition: opacity 0.5s ease; /* Suaviza la desaparición */
}
#loader-overlay.hidden {
  opacity: 0;
  pointer-events: none;
}
#loader-overlay img {
  max-width: 1000px;
  animation: fadeInOut 1.5s infinite;
}
@keyframes fadeInOut {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}
</style>

	
</head>
<body>

<!-- Banner superior -->
<div class="text-center">
    <img src="images/baner_prueba.jpg" alt="Banner EmePictures" class="img-fluid w-100">
</div>

<div class="menu-toggle" id="menuToggle">
    <i class="bars icon"></i>
</div>

<!-- Menú lateral con contacto en modal -->
<div class="side-menu" id="sideMenu">
    <a href="index.php">Inicio</a>
    
    <a href="#" id="abrirContacto">Casting Actriz</a> <!-- Modificado -->
	<a href="dashboard.php">Servicios para Creadores de Contenido</a>
	<a href="https://www.manyvids.com/Profile/1006856687/eme_oficial/Store/Items" target="_blank">Lenceria Shop</a>
</div>




<!-- Banner superior con contacto/soporte -->
<div class="banner-top">
  <div class="banner-content">
    <div>

      
<div class="floating-btn-container">
  <a href="https://www.instagram.com/tu_usuario" target="_blank" class="btn-instagram">
    <i class="fab fa-instagram"></i>
  </a>
 
</div>




<!-- NUEVO: Menú desplegable Lencería Shop con botón parpadeante -->
<div class="ui floating dropdown button" style="margin-left: 20px; background-color: #ffe0e9; color: #000; border: 1px solid #ccc;">
    <img src="images/LogoMV.jpeg" alt="Lencería Shop" style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;">
    Lencería Shop
    <div class="menu">
        <a class="item" href="https://www.manyvids.com/StoreItem/741599/outfit-briseida-myers-1" target="_blank">Outfit Briseida Myers 1</a>
        <a class="item" href="https://www.manyvids.com/StoreItem/741602/outfit-briseida-myers-2" target="_blank">Outfit Briseida Myers 2</a>
        <a class="item" href="https://www.manyvids.com/StoreItem/741596/outfit-lisi-kitty-1" target="_blank">Outfit Lisi Kitty 1</a>
        <a class="item" href="https://www.manyvids.com/StoreItem/741595/outfit-lisi-kitty-2" target="_blank">Outfit Lisi Kitty 2</a>
        <a class="item" href="https://www.manyvids.com/StoreItem/741594/outfit-lisi-kitty-3" target="_blank">Outfit Lisi Kitty 3</a>
    </div>
</div>

      <!-- Botones -->
      <button class="ui green button" id="btn-registro-suscripcion">
        <i class="user plus icon"></i> Hazte Miembro
      </button>
      <button class="ui primary button" id="open-login">
        <i class="sign-in icon"></i> Iniciar Sesión
      </button>
    </div>
  </div>
</div>


<div class="floating-btn-container">

  
  <a href="https://x.com/tu_usuario" target="_blank" class="btn-x">
    <i class="fab fa-x-twitter"></i>
  </a>
  
</div>









<!-- Carrusel -->
<div class="ui segment" id="carrusel-semantic" style="background: #000000; position: relative; overflow: hidden; padding: 0; width: 100%; max-width: 100vw; margin: 0 auto; height: 198px;">

  <div class="ui items" id="carrusel-slides" style="display: flex; transition: transform 0.5s ease; height: 198px;">
    <?php foreach ($imagenes as $index => $imgPath): ?>
      <div class="item carrusel-slide" style="flex: 0 0 auto; width: 264px; height: 198px; padding: 1px;">
        <img src="<?= htmlspecialchars($imgPath) ?>" style="width: 100%; height: 100%;" alt="Imagen <?= $index + 1 ?>">
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Botones -->
  <button class="ui circular icon button carrusel-control prev" style="position: absolute; top: 50%; left: 5px; transform: translateY(-50%); z-index: 1000;">
    <i class="chevron left icon"></i>
  </button>
  <button class="ui circular icon button carrusel-control next" style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%); z-index: 1000;">
    <i class="chevron right icon"></i>
  </button>

</div>



  <!-- Título y descripción debajo del carrusel -->
  <div class="ui container" style="margin-top: 30px;">
    <div class="ui stackable grid">
      <div class="sixteen wide column">
        <h2 class="ui header" style="color: #ffffff;">
          <div class="content">
            Videos
            <div class="sub header" style="color: #ffffff;">Haz clic en un video para verlo en grande</div>
          </div>
        </h2>

        <!-- Galería en rejilla -->
        <div class="ui three stackable cards">
          <?php
		  
		  // Definir límite por página
$videosPorPagina = 9;

// Obtener la página actual desde la URL, por defecto 1
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;

// Calcular el offset
$offset = ($paginaActual - 1) * $videosPorPagina;

// Contar total de videos
$totalVideos = $pdo->query("SELECT COUNT(*) FROM videos WHERE ruta LIKE 'uploads/portada/%'")->fetchColumn();
$totalPaginas = ceil($totalVideos / $videosPorPagina);

// Obtener videos de la página actual
$stmt = $pdo->prepare("SELECT titulo, descripcion, ruta, miniatura 
                       FROM videos 
                       WHERE ruta LIKE 'uploads/portada/%'
                       ORDER BY fecha_subida DESC
                       LIMIT :offset, :limite");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limite', $videosPorPagina, PDO::PARAM_INT);
$stmt->execute();
            //$stmt = $pdo->query("SELECT titulo, descripcion, ruta, miniatura FROM videos ORDER BY fecha_subida DESC");
            $videosJS = []; // Array para pasar a JavaScript
            while ($video = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $titulo = htmlspecialchars($video['titulo']);
                $descripcion = htmlspecialchars($video['descripcion']);
                $ruta = htmlspecialchars($video['ruta']);
                $miniatura = htmlspecialchars($video['miniatura']);
                $vistas = rand(50, 500);

                // Filtrar solo rutas que comiencen con uploads/portada/
                if (strpos($ruta, 'uploads/portada/') !== 0) {
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
                <div class="ui card video-card" style="background: #ffe0e9;"
                    data-src="' . $ruta . '"
                    data-title="' . $titulo . '"
                    data-description="' . $descripcion . '"
                    data-views="' . $vistas . '">
                    <div class="image video-thumb">
                        <img src="' . $miniatura . '" alt="Miniatura de ' . $titulo . '">
                    </div>
                    <div class="content">
                        <div class="header video-title">' . $titulo . '</div>
                    </div>
                </div>


 				
				';
            }
          ?>
		  
<!-- Controles de paginación -->
<div class="ui pagination menu" style="margin-top: 20px; margin: 50px auto">
    <?php if ($paginaActual > 1): ?>
        <a class="item" style="background: #ff0080; href="?pagina=<?= $paginaActual - 1 ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a class="item <?= ($i === $paginaActual) ? 'active' : '' ?>" href="?pagina=<?= $i ?>" style="background: #ffe0e9;">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a class="item" style="background: #ff0080; href="?pagina=<?= $paginaActual + 1 ?>">Siguiente &raquo;</a>
    <?php endif; ?>
</div>

		  
        </div>

      </div>
    </div>
  </div>

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




<!-- Banner inferior -->
<div class="banner banner-bottom">
    <div class="banner-content">
        <div>© 2025 Eme Pictures S.L. Todos los derechos reservados.</div>
        <div>
            <a href="/terminos.php">Términos y Condiciones</a>
            <a href="/privacidad.php">Política de Privacidad</a>
            <a href="/cookies.php">Política de Cookies</a>
        </div>
    </div>
</div>

<!-- Modal de Login -->
<div class="ui small modal" id="loginModal">
    <i class="close icon"></i>
    <div class="header" style="background-color: #ffe0e9;">Iniciar Sesión</div>
    <div class="content" style="background-color: #ffe0e9;">
        <div id="form-container">
            <?php if ($error): ?><div class="ui negative message"><?= $error ?></div><?php endif; ?>
            <?php if ($exito): ?><div class="ui positive message"><?= $exito ?></div><?php endif; ?>
            <form class="ui form" method="post" id="loginForm">
                <input type="hidden" name="login" value="1">
                <div class="field"><label>Usuario</label><input type="text" name="usuario" required></div>
                <div class="field"><label>Clave</label><input type="password" name="clave" required></div>
                <div style="margin-bottom: 1em;"><a href="#" id="link-recuperar">¿Olvidaste tu contraseña?</a></div>
                <button type="submit" class="ui primary button fluid">Entrar</button>
            </form>
        </div>
    </div>
    <!-- div class="actions" style="background-color: #ffe0e9;" -->
        <!-- a href="registro.php" class="ui green button">Crear una cuenta</a-->
        <!-- div class="ui cancel button" style="background-color: red;">Cancelar</div>
    </div-->
</div>

<!-- Modal de Video -->
<!--div class="ui fullscreen modal" id="videoModal">
    <i class="close icon"></i>
    <div class="content" style="padding:0;">
        <video id="modalVideoPlayer" controls style="width:100%; height: 100vh;">
            <source src="" type="video/mp4">
        </video>
    </div>
</div-->

<!-- Modal de Contacto -->
<div class="ui small modal" id="contactoModal">
    <i class="close icon"></i>
    <div class="header">Formulario de Contacto</div>
    <div class="content" id="contacto-content">
        <div class="ui active inline loader"></div>
        <p>Cargando formulario de contacto...</p>
    </div>
</div>


<!-- Modal Edad -->
<div class="ui tiny modal" id="modalEdad" style="text-align: center;">
  <div class="header" style="background-color: #ffe0e9;">Confirmación de Edad</div>
  <div class="content" style="background-color: #ffe0e9;">
    <p style="font-size: 1.2em;">¿Eres mayor de 18 años?</p>
	<p style="font-size: 1.2em;">Al acceder este sitio web, reconozco que tengo 18 años o más y acepto los Términos de servicio, que están disponibles</p>
  </div>
  <div class="actions" style="justify-content: center; text-align: center; background-color: #ffe0e9;">
    <button class="ui red button" id="noMayorEdad">No</button>
    <button class="ui green button" id="siMayorEdad">Sí</button>
  </div>
</div>

<div class="ui small modal" id="modalRegistroMiembro">
  <i class="close icon"></i>
  <div class="header" style="background-color: #ffe0e9;">Únete a Eme Pictures</div>
  <div class="content" style="background-color: #ffe0e9;">
<form class="ui form" id="formRegistroMiembro">
  <div class="field">
    <label>Email</label>
    <input type="email" name="email" required>
  </div>
  <div class="field">
    <label>Contraseña</label>
    <input type="password" name="password" required>
  </div>
  <div class="field">
    <label>Repetir Contraseña</label>
    <input type="password" name="password2" required>
  </div>

  <div class="grouped fields">
    <label>Selecciona una tarifa:</label>
    <div class="field">
      <div class="ui radio checkbox">
        <input type="radio" name="plan" value="mensual" checked>
        <label>Mensual - 5,99€</label>
      </div>
    </div>
    <div class="field">
      <div class="ui radio checkbox">
        <input type="radio" name="plan" value="trimestral">
        <label>Trimestral - 14,99€</label>
      </div>
    </div>
    <div class="field">
      <div class="ui radio checkbox">
        <input type="radio" name="plan" value="anual">
        <label>Anual - 49,99€</label>
      </div>
    </div>
  </div>

  <button type="submit" class="ui green fluid button">Únete</button>
</form>

  </div>
</div>

</body>

<!-- JS y Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>

<!-- Pantalla de carga -->
<div id="loader-overlay">
  <img src="images/eme_horizontal.png" alt="Cargando...">
</div>

<script>
window.addEventListener("load", function(){
  document.getElementById("loader-overlay").style.display = "none";
});
</script>


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
  
  
  <script>
    window.allVideos = <?php echo json_encode($videosJS, JSON_UNESCAPED_UNICODE); ?>;
  </script>
  
<script>
    $('#open-login').click(function() {
        $('#loginModal').modal('show');
    });

    $('.video-card').click(function() {
        const videoSrc = $(this).data('video-src');
        const videoPlayer = $('#modalVideoPlayer');
        videoPlayer.attr('src', videoSrc);
        $('#videoModal').modal({
            onHidden: function() {
                videoPlayer[0].pause();
                videoPlayer.attr('src', '');
            }
        }).modal('show');
    });

    $('#menuToggle').click(function() {
        $('#sideMenu').toggleClass('active');
    });

    $(document).on('click', '#link-recuperar', function(e) {
        e.preventDefault();
        $('#form-container').load('recuperar_form.php');
    });

    $(document).on('click', '#volver-login', function() {
        $.ajax({
            url: window.location.href,
            success: function(data) {
                const form = $(data).find('#form-container').html();
                $('#form-container').html(form);
            }
        });
    });

    // NUEVO: Modal de contacto dinámico
    $('#abrirContacto').click(function(e) {
        e.preventDefault();
        $('#contacto-content').html('<div class="ui active inline loader"></div><p>Cargando formulario de contacto...</p>');
        $('#contactoModal').modal('show');
        $('#contacto-content').load('contacto.php');
    });
</script>

<script>
  // Espera 2 segundos y luego oculta el overlay
  setTimeout(() => {
    document.getElementById('loader-overlay').classList.add('hidden');
  }, 3000);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
	
	$('.ui.radio.checkbox').checkbox();
	
  const carrusel = document.getElementById('carrusel-slides');
  const slides = document.querySelectorAll('.carrusel-slide');
  const prevBtn = document.querySelector('.carrusel-control.prev');
  const nextBtn = document.querySelector('.carrusel-control.next');

  const slideWidth = slides[0].offsetWidth;
  const visibleSlides = Math.floor(carrusel.parentElement.offsetWidth / slideWidth);
  const maxIndex = slides.length - visibleSlides;

  let currentIndex = 0;
  let autoScrollInterval;

  function updateCarrusel() {
    const offset = -currentIndex * slideWidth;
    carrusel.style.transform = `translateX(${offset}px)`;
  }

  function nextSlide() {
    if (currentIndex < maxIndex) {
      currentIndex++;
    } else {
      currentIndex = 0;
    }
    updateCarrusel();
  }

  function prevSlide() {
    if (currentIndex > 0) {
      currentIndex--;
    } else {
      currentIndex = maxIndex;
    }
    updateCarrusel();
  }

  nextBtn.addEventListener('click', () => {
    nextSlide();
    resetAutoScroll();
  });

  prevBtn.addEventListener('click', () => {
    prevSlide();
    resetAutoScroll();
  });

  function startAutoScroll() {
    autoScrollInterval = setInterval(nextSlide, 4000); // cada 4 segundos
  }

  function resetAutoScroll() {
    clearInterval(autoScrollInterval);
    startAutoScroll();
  }

  window.addEventListener('resize', () => {
    const newVisible = Math.floor(carrusel.parentElement.offsetWidth / slideWidth);
    currentIndex = Math.min(currentIndex, slides.length - newVisible);
    updateCarrusel();
  });

  updateCarrusel();
  startAutoScroll(); // << Aquí se activa el autoplay
});
</script>





<script>
  $(document).ready(function () {
	  
	   $('.ui.floating.dropdown').dropdown();
	   //console.log('Dropdown inicializado');

	   
       $('.blinking-button.ui.dropdown').dropdown();
	   console.log('Dropdown inicializado');
    $('#modalEdad')
      .modal({
        closable: false,
        autofocus: true,
        observeChanges: true,
        onDeny: function () {
          window.location.href = "https://www.google.com";
          return false;
        },
        onApprove: function () {
          // no hacemos nada, se cierra el modal
        }
      })
      .modal('show');
  });

  $('#noMayorEdad').click(function () {
    window.location.href = "https://www.google.com";
  });

  $('#siMayorEdad').click(function () {
    $('#modalEdad').modal('hide');
  });
</script>

<script src="https://js.stripe.com/v3/"></script>


<script>
  const stripeLinks = {
    mensual: 'https://buy.stripe.com/test_8x200k9Ha1bB1eYdeL4ko00',
    trimestral: 'https://buy.stripe.com/test_7sI4ikgW26ll1eY6op',
    anual: 'https://buy.stripe.com/test_4gw4hU4hm5FF5pC6op'
  };

  $('#btn-registro-suscripcion').click(function () {
    $('#modalRegistroMiembro').modal('show');
  });

  $('#formRegistroMiembro').submit(function (e) {
    e.preventDefault();

    const password = $('input[name="password"]').val();
    const password2 = $('input[name="password2"]').val();
    if (password !== password2) {
      alert('Las contraseñas no coinciden');
      return;
    }

	
	const email = $('input[name="email"]').val();
    const plan = $('input[name="plan"]:checked').val();
    const link = stripeLinks[plan];
    
    if (link) {
      // ANTES: redirigir directamente
// window.location.href = link;

$.ajax({
  url: 'crear_suscripcion.php',
  method: 'POST',
  data: { email, password, plan },
  success: function (link) {
    window.location.href = link;
  },
  error: function (xhr) {
    alert(xhr.responseText || 'Error al registrar usuario');
  }
});
    } else {
      alert('Error: No se encontró el enlace de pago para este plan.');
    }
  });
</script>








</html>
