<?php
/**
 * Funções para renderização do layout e componentes da interface
 */

// Verifica se o arquivo de configuração foi carregado
if (!defined('SATELITE_CONFIG_LOADED')) {
    require_once __DIR__ . '/../config.php';
}

/**
 * Renderiza o cabeçalho HTML
 * 
 * @param string $title Título da página
 * @param string $description Descrição da página para SEO
 * @return void
 */
function render_header($title = '', $description = '') {
    global $config;
    
    $site_title = $title ? $title . ' - ' . $config['site_name'] : $config['site_name'];
    $site_description = $description ?: $config['site_description'];
    
    echo '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="' . $site_description . '">
    <title>' . $site_title . '</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>
    <div class="stars-background"></div>
    <div class="overlay-grid"></div>
    <div class="container">
        <header class="main-header">
            <div class="logo">
                <a href="index.php">
                    <span class="logo-icon"><i class="fas fa-satellite"></i></span>
                    <span class="logo-text">' . $config['site_name'] . '</span>
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="nav-link"><i class="fas fa-home"></i> Início</a></li>
                    <li><a href="terra.php" class="nav-link"><i class="fas fa-globe-americas"></i> Terra</a></li>
                    <li><a href="espaco.php" class="nav-link"><i class="fas fa-star"></i> Espaço</a></li>
                    <li><a href="iss.php" class="nav-link"><i class="fas fa-space-shuttle"></i> ISS</a></li>
                    <li><a href="telescopio.php" class="nav-link"><i class="fas fa-satellite-dish"></i> Telescópio Público</a></li>
                    <li><a href="nasa.php" class="nav-link"><i class="fas fa-rocket"></i> Biblioteca NASA</a></li>
                    <li><a href="sobre.php" class="nav-link"><i class="fas fa-info-circle"></i> Sobre</a></li>
                </ul>
            </nav>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </header>
        <main class="content">';
}

/**
 * Renderiza o rodapé HTML
 * 
 * @return void
 */
function render_footer() {
    global $config;
    
    // Primeira parte do rodapé
    echo '</main>
        <footer class="main-footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sobre ' . $config['site_name'] . '</h3>
                    <p>Visualização de imagens de satélites em tempo real da Terra e do espaço.</p>
                </div>
                <div class="footer-section">
                    <h3>Links Rápidos</h3>
                    <ul>
                        <li><a href="index.php">Início</a></li>
                        <li><a href="terra.php">Terra</a></li>
                        <li><a href="espaco.php">Espaço</a></li>
                        <li><a href="iss.php">ISS</a></li>
                        <li><a href="sobre.php">Sobre</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Créditos</h3>
                    <p>Imagens fornecidas por: NASA, ESA, NOAA</p>
                    <p>APIs utilizadas: NASA EPIC, APOD, ISS Tracker</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; ' . date('Y') . ' ' . $config['site_name'] . '. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>';
    
    // Scripts separados para evitar problemas de sintaxe
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/stars.js"></script>';
    
    // Script de inicialização
    echo '<script>
    // Inicializa os dados quando a página carregar
    window.onload = function() {
        // Inicializa os dados dos satélites
        if (typeof initSatelliteData === "function") {
            initSatelliteData();
        }
        
        // Inicializa o rastreamento da ISS
        if (typeof startISSTracking === "function" && document.getElementById("iss-map")) {
            startISSTracking();
        }
    };
    </script>
</body>
</html>';
}

/**
 * Renderiza um card de satélite
 * 
 * @param string $title Título do card
 * @param string $image URL da imagem
 * @param string $description Descrição do satélite
 * @param string $link Link para mais detalhes
 * @return void
 */
function render_satellite_card($title, $image, $description, $link = '#') {
    echo '<div class="satellite-card">
        <div class="satellite-image">
            <img src="' . $image . '" alt="' . $title . '">
        </div>
        <div class="satellite-info">
            <h3>' . $title . '</h3>
            <p>' . $description . '</p>
            <a href="' . $link . '" class="btn-view">Ver Imagens <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>';
}

/**
 * Renderiza um loader de carregamento
 * 
 * @param string $message Mensagem de carregamento
 * @return void
 */
function render_loader($message = 'Carregando imagens de satélite...') {
    echo '<div class="loader-container">
        <div class="loader">
            <div class="loader-ring"></div>
            <div class="loader-planet"></div>
        </div>
        <p class="loader-text">' . $message . '</p>
    </div>';
}

/**
 * Renderiza uma galeria de imagens
 * 
 * @param array $images Array de imagens com título e URL
 * @return void
 */
function render_image_gallery($images) {
    echo '<div class="image-gallery">';
    foreach ($images as $image) {
        echo '<div class="gallery-item">
            <div class="gallery-image">
                <img src="' . $image['url'] . '" alt="' . $image['title'] . '" loading="lazy">
            </div>
            <div class="gallery-caption">
                <h4>' . $image['title'] . '</h4>
                <p>' . ($image['description'] ?? '') . '</p>
                <span class="gallery-date">' . ($image['date'] ?? '') . '</span>
            </div>
        </div>';
    }
    echo '</div>';
}

/**
 * Renderiza um mapa interativo
 * 
 * @param string $id ID do elemento
 * @param float $lat Latitude inicial
 * @param float $lng Longitude inicial
 * @param int $zoom Nível de zoom inicial
 * @return void
 */
function render_interactive_map($id = 'map', $lat = 0, $lng = 0, $zoom = 2) {
    echo '<div id="' . $id . '" class="interactive-map"></div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initMap("' . $id . '", ' . $lat . ', ' . $lng . ', ' . $zoom . ');
        });
    </script>';
}

/**
 * Renderiza uma seção de estatísticas
 * 
 * @param array $stats Array de estatísticas
 * @return void
 */
function render_stats_section($stats) {
    echo '<div class="stats-section">';
    foreach ($stats as $stat) {
        echo '<div class="stat-item">
            <div class="stat-icon"><i class="' . $stat['icon'] . '"></i></div>
            <div class="stat-value">' . $stat['value'] . '</div>
            <div class="stat-label">' . $stat['label'] . '</div>
        </div>';
    }
    echo '</div>';
}

/**
 * Renderiza uma mensagem de erro
 * 
 * @param string $message Mensagem de erro
 * @return void
 */
function render_error($message) {
    echo '<div class="error-message">
        <div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="error-text">' . $message . '</div>
    </div>';
}

/**
 * Renderiza uma notificação
 * 
 * @param string $message Mensagem da notificação
 * @param string $type Tipo da notificação (success, info, warning, error)
 * @return void
 */
function render_notification($message, $type = 'info') {
    $icon = '';
    switch ($type) {
        case 'success':
            $icon = 'fas fa-check-circle';
            break;
        case 'warning':
            $icon = 'fas fa-exclamation-triangle';
            break;
        case 'error':
            $icon = 'fas fa-times-circle';
            break;
        default:
            $icon = 'fas fa-info-circle';
    }
    
    echo '<div class="notification notification-' . $type . '">
        <div class="notification-icon"><i class="' . $icon . '"></i></div>
        <div class="notification-message">' . $message . '</div>
        <div class="notification-close"><i class="fas fa-times"></i></div>
    </div>';
}
?>
