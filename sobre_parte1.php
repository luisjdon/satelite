<?php
/**
 * SatéliteVision - Página Sobre (Parte 1)
 * Informações sobre o projeto e as APIs utilizadas
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Sobre', 'Informações sobre o projeto SatéliteVision e as APIs utilizadas');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Sobre o SatéliteVision</h1>
    <p class="hero-subtitle">Conheça mais sobre este projeto futurístico de visualização de imagens de satélites em tempo real e as tecnologias utilizadas.</p>
</section>

<!-- Seção Sobre o Projeto -->
<section class="featured-section">
    <h2 class="section-title">O Projeto</h2>
    
    <div class="about-content">
        <div class="about-image">
            <img src="img/about-project.jpg" alt="SatéliteVision" class="animate-float">
        </div>
        
        <div class="about-text">
            <p>O <strong>SatéliteVision</strong> é um projeto inovador desenvolvido para proporcionar uma experiência visual única de exploração do nosso planeta e do espaço através de imagens capturadas por satélites e telescópios espaciais.</p>
            
            <p>Com uma interface futurística e intuitiva, o site permite aos usuários:</p>
            
            <ul class="feature-list">
                <li><i class="fas fa-check-circle"></i> Visualizar imagens da Terra em tempo real</li>
                <li><i class="fas fa-check-circle"></i> Acompanhar a localização da Estação Espacial Internacional</li>
                <li><i class="fas fa-check-circle"></i> Explorar imagens do espaço profundo capturadas por telescópios</li>
                <li><i class="fas fa-check-circle"></i> Monitorar eventos naturais na Terra detectados por satélites</li>
                <li><i class="fas fa-check-circle"></i> Descobrir quando a ISS será visível em sua localização</li>
            </ul>
            
            <p>Este projeto utiliza dados de diversas agências espaciais e serviços meteorológicos para fornecer informações precisas e atualizadas sobre nosso planeta e o universo.</p>
        </div>
    </div>
</section>

<!-- Seção APIs Utilizadas -->
<section class="featured-section">
    <h2 class="section-title">APIs Utilizadas</h2>
    <p class="section-description">O SatéliteVision integra-se com diversas APIs para fornecer dados em tempo real.</p>
    
    <div class="api-grid">
        <div class="api-card">
            <div class="api-icon">
                <i class="fas fa-globe-americas"></i>
            </div>
            <div class="api-info">
                <h3>NASA EPIC API</h3>
                <p>Fornece imagens da Terra inteira capturadas pela câmera EPIC (Earth Polychromatic Imaging Camera) a bordo do satélite DSCOVR.</p>
                <a href="https://epic.gsfc.nasa.gov/about/api" target="_blank" class="api-link">Documentação <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
        
        <div class="api-card">
            <div class="api-icon">
                <i class="fas fa-meteor"></i>
            </div>
            <div class="api-info">
                <h3>NASA EONET API</h3>
                <p>Fornece dados sobre eventos naturais na Terra, como incêndios, erupções vulcânicas, tempestades e outros fenômenos.</p>
                <a href="https://eonet.gsfc.nasa.gov/docs/v3" target="_blank" class="api-link">Documentação <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
        
        <div class="api-card">
            <div class="api-icon">
                <i class="fas fa-space-shuttle"></i>
            </div>
            <div class="api-info">
                <h3>ISS Tracker API</h3>
                <p>Fornece a localização em tempo real da Estação Espacial Internacional enquanto ela orbita a Terra.</p>
                <a href="http://open-notify.org/Open-Notify-API/ISS-Location-Now/" target="_blank" class="api-link">Documentação <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
        
        <div class="api-card">
            <div class="api-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="api-info">
                <h3>NASA APOD API</h3>
                <p>Fornece a Imagem Astronômica do Dia, selecionada pela NASA, junto com explicações escritas por astrônomos profissionais.</p>
                <a href="https://api.nasa.gov/" target="_blank" class="api-link">Documentação <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
        
        <div class="api-card">
            <div class="api-icon">
                <i class="fas fa-cloud-sun"></i>
            </div>
            <div class="api-info">
                <h3>GOES Satellite API</h3>
                <p>Fornece imagens meteorológicas de alta resolução capturadas pelos satélites GOES (Geostationary Operational Environmental Satellite).</p>
                <a href="https://www.star.nesdis.noaa.gov/goes/index.php" target="_blank" class="api-link">Documentação <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
        
        <div class="api-card">
            <div class="api-icon">
                <i class="fas fa-satellite"></i>
            </div>
            <div class="api-info">
                <h3>Landsat API</h3>
                <p>Fornece acesso às imagens de alta resolução da superfície terrestre capturadas pelos satélites Landsat.</p>
                <a href="https://www.usgs.gov/landsat-missions/landsat-data-access" target="_blank" class="api-link">Documentação <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
    </div>
</section>
