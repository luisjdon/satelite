<?php
/**
 * SatéliteVision - Página da ISS (Parte 1)
 * Visualização da Estação Espacial Internacional
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('ISS', 'Visualização e rastreamento da Estação Espacial Internacional em tempo real');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Estação Espacial Internacional</h1>
    <p class="hero-subtitle">Acompanhe em tempo real a localização da Estação Espacial Internacional (ISS) enquanto ela orbita nosso planeta a uma velocidade de 27.600 km/h.</p>
</section>

<!-- Seção de Rastreamento da ISS -->
<section class="featured-section">
    <h2 class="section-title">Rastreamento em Tempo Real</h2>
    <p class="section-description">A ISS completa uma órbita ao redor da Terra a cada 90 minutos, viajando a uma altitude média de 408 km.</p>
    
    <div class="iss-tracker-container">
        <div class="iss-data-panel">
            <div class="iss-data-header">
                <h3>Dados da ISS</h3>
                <span class="iss-status online">Online</span>
            </div>
            
            <div class="iss-data-grid">
                <div class="iss-data-item">
                    <span class="iss-data-label">Latitude:</span>
                    <span id="iss-lat" class="iss-data-value">Carregando...</span>
                </div>
                <div class="iss-data-item">
                    <span class="iss-data-label">Longitude:</span>
                    <span id="iss-lng" class="iss-data-value">Carregando...</span>
                </div>
                <div class="iss-data-item">
                    <span class="iss-data-label">Altitude:</span>
                    <span id="iss-altitude" class="iss-data-value">408 km</span>
                </div>
                <div class="iss-data-item">
                    <span class="iss-data-label">Velocidade:</span>
                    <span id="iss-speed" class="iss-data-value">27.600 km/h</span>
                </div>
                <div class="iss-data-item">
                    <span class="iss-data-label">Órbita:</span>
                    <span id="iss-orbit" class="iss-data-value">90 minutos</span>
                </div>
                <div class="iss-data-item">
                    <span class="iss-data-label">Última atualização:</span>
                    <span id="iss-update-time" class="iss-data-value"><?php echo date('d/m/Y H:i:s'); ?></span>
                </div>
            </div>
            
            <div class="iss-actions">
                <button id="iss-refresh" class="btn-view">Atualizar <i class="fas fa-sync"></i></button>
                <button id="iss-center" class="btn-view">Centralizar <i class="fas fa-crosshairs"></i></button>
            </div>
        </div>
        
        <!-- Mapa da ISS -->
        <div id="iss-map" class="iss-map"></div>
    </div>
    
    <!-- Adiciona o Leaflet para o mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</section>
