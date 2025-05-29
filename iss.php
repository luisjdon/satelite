<?php
/**
 * SatéliteVision - Página da ISS
 * Visualização e rastreamento da Estação Espacial Internacional
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('ISS', 'Rastreamento em tempo real da Estação Espacial Internacional');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Estação Espacial Internacional</h1>
    <p class="hero-subtitle">Acompanhe a ISS em tempo real enquanto ela orbita nosso planeta a uma velocidade de 27.600 km/h, completando 15,5 órbitas por dia.</p>
</section>

<!-- Seção de Rastreamento da ISS -->
<section id="iss-tracking" class="featured-section">
    <h2 class="section-title">🛰️ Rastreamento da ISS em tempo real</h2>
    <p class="section-description">Veja a localização atual da Estação Espacial Internacional e assista à transmissão ao vivo.</p>
    
    <!-- Vídeo ISS -->
    <div class="video-container" style="margin-bottom: 20px;">
        <iframe width="100%" height="400"
                src="https://www.youtube.com/embed/H999s0P1Er0?autoplay=1&mute=1"
                title="ISS Live HD"
                frameborder="0" allowfullscreen>
        </iframe>
    </div>
    
    <!-- Mapa -->
    <div id="map" style="height: 400px; margin-top: 20px; border-radius: 10px;"></div>
    <div id="iss-info" class="iss-info">
        <div class="iss-info-item">
            <span class="iss-info-label">Latitude:</span>
            <span id="iss-lat" class="iss-info-value">Carregando...</span>
        </div>
        <div class="iss-info-item">
            <span class="iss-info-label">Longitude:</span>
            <span id="iss-lng" class="iss-info-value">Carregando...</span>
        </div>
        <div class="iss-info-item">
            <span class="iss-info-label">Velocidade:</span>
            <span class="iss-info-value">27.600 km/h</span>
        </div>
        <div class="iss-info-item">
            <span class="iss-info-label">Altitude:</span>
            <span class="iss-info-value">408 km</span>
        </div>
    </div>
</section>

<!-- Seção de Informações da ISS -->
<section id="iss-info" class="featured-section">
    <h2 class="section-title">Sobre a Estação Espacial Internacional</h2>
    <p class="section-description">A ISS é um laboratório de pesquisa em órbita, habitado continuamente desde novembro de 2000. É uma colaboração entre as agências espaciais dos EUA, Rússia, Europa, Japão e Canadá.</p>
    
    <div class="iss-facts">
        <div class="iss-fact">
            <h3>Tamanho</h3>
            <p>109m x 73m (aproximadamente do tamanho de um campo de futebol)</p>
        </div>
        <div class="iss-fact">
            <h3>Peso</h3>
            <p>Aproximadamente 420.000 kg</p>
        </div>
        <div class="iss-fact">
            <h3>Tripulação</h3>
            <p>Normalmente 6 astronautas</p>
        </div>
        <div class="iss-fact">
            <h3>Lançamento</h3>
            <p>Primeiro módulo lançado em 1998</p>
        </div>
    </div>
</section>

<!-- Inclui o Leaflet para o mapa -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<!-- Script para o mapa da ISS -->
<script>
// Inicializa o mapa
const map = L.map('map').setView([0, 0], 2);

// Adiciona o layer do mapa
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Cria um ícone personalizado para a ISS
const issIcon = L.icon({
  iconUrl: 'img/iss-icon.png',
  iconSize: [50, 30],
  iconAnchor: [25, 15]
});

// Adiciona o marcador da ISS
const marker = L.marker([0, 0], {icon: issIcon}).addTo(map);

// Função para atualizar a posição da ISS
function updateISS() {
  fetch('http://api.open-notify.org/iss-now.json')
    .then(res => res.json())
    .then(data => {
      const { latitude, longitude } = data.iss_position;
      
      // Atualiza o marcador e centraliza o mapa
      marker.setLatLng([latitude, longitude]);
      map.setView([latitude, longitude], 3);
      
      // Atualiza as informações de latitude e longitude
      document.getElementById('iss-lat').innerText = latitude;
      document.getElementById('iss-lng').innerText = longitude;
    })
    .catch(err => {
      console.error("Erro ao obter posição da ISS:", err);
    });
}

// Atualiza a posição inicialmente e a cada 5 segundos
updateISS();
setInterval(updateISS, 5000);
</script>

<?php
// Renderiza o rodapé
render_footer();
?>
