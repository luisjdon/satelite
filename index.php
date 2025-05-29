<?php
/**
 * SatéliteVision - Página Inicial
 * Site futurístico para visualização de imagens de satélites
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Página Inicial', 'Visualização de imagens de satélites em tempo real da Terra e do espaço');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">SatéliteVision</h1>
    <p class="hero-subtitle">Explore o planeta Terra e o espaço através de imagens de satélites em tempo real. Uma experiência visual única e futurística para observar nosso mundo e além.</p>
    
    <div class="hero-buttons">
        <a href="terra.php" class="btn btn-primary">Explorar a Terra <i class="fas fa-globe-americas"></i></a>
        <a href="espaco.php" class="btn btn-secondary">Explorar o Espaço <i class="fas fa-star"></i></a>
    </div>
</section>


<!-- Seção de Estatísticas -->
<section class="featured-section">
    <h2 class="section-title">Dados em Tempo Real</h2>
    
    <?php
    // Estatísticas para exibição
    $stats = [
        [
            'icon' => 'fas fa-satellite',
            'value' => '7.500+',
            'label' => 'Satélites em Órbita'
        ],
        [
            'icon' => 'fas fa-camera',
            'value' => '1.2 TB',
            'label' => 'Dados Gerados por Dia'
        ],
        [
            'icon' => 'fas fa-globe-americas',
            'value' => '24/7',
            'label' => 'Monitoramento Contínuo'
        ],
        [
            'icon' => 'fas fa-space-shuttle',
            'value' => '27.600 km/h',
            'label' => 'Velocidade da ISS'
        ]
    ];
    
    // Renderiza as estatísticas
    render_stats_section($stats);
    ?>
</section>

<!-- Seção de Imagens da NASA em Tempo Real -->
<section class="featured-section">
    <h2 class="section-title">🌌 Imagens Espaciais da NASA em Tempo Real</h2>
    <p class="section-description">Explore as mais recentes e impressionantes imagens do acervo da NASA, atualizadas em tempo real.</p>
    
    <div id="nasa-images-container" class="nasa-home-gallery">
        <div class="loader">Carregando imagens da NASA...</div>
    </div>
    
    <div class="view-more-container">
        <a href="nasa.php" class="btn btn-secondary">Ver mais imagens <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <script>
    // Função para buscar imagens da NASA em tempo real
    function fetchNASAImages() {
        // Termos de busca aleatórios para variar as imagens
        const searchTerms = ['galaxy', 'nebula', 'supernova', 'mars', 'jupiter', 'saturn', 'moon', 'hubble', 'webb'];
        const randomTerm = searchTerms[Math.floor(Math.random() * searchTerms.length)];
        
        // URL da API da NASA
        const url = `https://images-api.nasa.gov/search?q=${randomTerm}&media_type=image`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const items = data.collection.items;
                if (!items || items.length === 0) {
                    document.getElementById('nasa-images-container').innerHTML = 
                        '<p class="error-message">Nenhuma imagem encontrada. Tente novamente mais tarde.</p>';
                    return;
                }
                
                // Limita a 4 imagens para a página inicial
                const limitedItems = items.slice(0, 4);
                
                let html = '<div class="nasa-grid">';
                
                limitedItems.forEach(item => {
                    if (!item.links || !item.links[0] || !item.links[0].href) return;
                    
                    const img = item.links[0].href;
                    const title = item.data[0].title || 'Imagem NASA';
                    const date = item.data[0].date_created ? 
                        new Date(item.data[0].date_created).toLocaleDateString('pt-BR') : '';
                    
                    html += `
                        <div class="nasa-item">
                            <div class="nasa-image">
                                <img src="${img}" alt="${title}" loading="lazy" 
                                     onclick="window.location.href='nasa.php?q=${randomTerm}'">
                            </div>
                            <div class="nasa-info">
                                <h3>${title}</h3>
                                ${date ? `<p class="nasa-date">${date}</p>` : ''}
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                document.getElementById('nasa-images-container').innerHTML = html;
            })
            .catch(error => {
                console.error('Erro ao buscar imagens da NASA:', error);
                document.getElementById('nasa-images-container').innerHTML = 
                    '<p class="error-message">Erro ao carregar imagens. Tente novamente mais tarde.</p>';
            });
    }
    
    // Carrega as imagens quando a página terminar de carregar
    document.addEventListener('DOMContentLoaded', fetchNASAImages);
    </script>
    
    <style>
    .nasa-home-gallery {
        margin-top: 20px;
    }
    
    .nasa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .nasa-item {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    
    .nasa-item:hover {
        transform: translateY(-5px);
    }
    
    .nasa-image img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: opacity 0.3s;
    }
    
    .nasa-image img:hover {
        opacity: 0.8;
    }
    
    .nasa-info {
        padding: 15px;
    }
    
    .nasa-info h3 {
        margin-top: 0;
        color: #4da6ff;
        font-size: 16px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .nasa-date {
        color: #aaa;
        font-size: 14px;
        margin: 5px 0 0;
    }
    
    .view-more-container {
        text-align: center;
        margin-top: 25px;
    }
    
    .loader {
        text-align: center;
        padding: 30px;
        font-style: italic;
        color: #aaa;
    }
    
    .error-message {
        background: rgba(255, 50, 50, 0.2);
        border-left: 4px solid #ff3232;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
    }
    </style>
</section>

<!-- Seção de Imagem do Dia -->
<section class="featured-section">
    <h2 class="section-title">🌟 Imagem Astronômica do Dia</h2>
    <p class="section-description">A NASA seleciona diariamente uma imagem ou fotografia do nosso universo fascinante, junto com uma breve explicação escrita por um astrônomo profissional.</p>
    
    <div id="apod-container" class="apod-container">
        <div class="loader">Carregando imagem astronômica do dia...</div>
    </div>
    
    <script>
    // Função para buscar a imagem astronômica do dia da NASA
    function fetchAPOD() {
        // API Key da NASA
        const apiKey = "API";
        
        // URL da API APOD da NASA
        const url = `https://api.nasa.gov/planetary/apod?api_key=${apiKey}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                let html = '<div class="apod-card">';
                
                // Verifica se é uma imagem ou vídeo
                if (data.media_type === 'image') {
                    html += `<div class="apod-image">
                        <img src="${data.url}" alt="${data.title}" onclick="openAPODModal('${data.url}', '${data.title.replace(/'/g, "\\'")}')">                        
                    </div>`;
                } else if (data.media_type === 'video') {
                    html += `<div class="apod-video">
                        <iframe src="${data.url}" frameborder="0" allowfullscreen></iframe>
                    </div>`;
                }
                
                html += `<div class="apod-info">
                    <h3>${data.title}</h3>
                    <p class="apod-date">${new Date(data.date).toLocaleDateString('pt-BR')}</p>
                    <p class="apod-explanation">${data.explanation.substring(0, 300)}... <a href="javascript:void(0)" onclick="showFullExplanation('${data.explanation.replace(/'/g, "\\'")}')">Ler mais</a></p>
                </div></div>`;
                
                document.getElementById('apod-container').innerHTML = html;
            })
            .catch(error => {
                console.error('Erro ao buscar imagem astronômica do dia:', error);
                document.getElementById('apod-container').innerHTML = 
                    '<p class="error-message">Erro ao carregar a imagem astronômica do dia. Tente novamente mais tarde.</p>';
            });
    }
    
    // Função para abrir a imagem em tamanho completo
    function openAPODModal(url, title) {
        const modal = document.createElement('div');
        modal.className = 'apod-modal';
        modal.innerHTML = `
            <div class="apod-modal-content">
                <span class="apod-close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                <img src="${url}" alt="${title}">
                <h3>${title}</h3>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Fecha o modal ao clicar fora da imagem
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.remove();
            }
        });
    }
    
    // Função para mostrar a explicação completa
    function showFullExplanation(explanation) {
        const modal = document.createElement('div');
        modal.className = 'apod-modal';
        modal.innerHTML = `
            <div class="apod-modal-content apod-text-modal">
                <span class="apod-close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                <h3>Explicação Completa</h3>
                <p>${explanation}</p>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Fecha o modal ao clicar fora do texto
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.remove();
            }
        });
    }
    
    // Carrega a imagem astronômica do dia quando a página terminar de carregar
    document.addEventListener('DOMContentLoaded', fetchAPOD);
    </script>
    
    <style>
    .apod-container {
        margin-top: 20px;
    }
    
    .apod-card {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .apod-image img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        cursor: pointer;
        transition: opacity 0.3s;
    }
    
    .apod-image img:hover {
        opacity: 0.9;
    }
    
    .apod-video iframe {
        width: 100%;
        height: 500px;
        border: none;
    }
    
    .apod-info {
        padding: 20px;
    }
    
    .apod-info h3 {
        margin-top: 0;
        color: #4da6ff;
    }
    
    .apod-date {
        color: #aaa;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .apod-explanation {
        color: #ddd;
        line-height: 1.5;
    }
    
    .apod-explanation a {
        color: #4da6ff;
        text-decoration: none;
    }
    
    /* Modal */
    .apod-modal {
        display: block;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: auto;
    }
    
    .apod-modal-content {
        margin: 5% auto;
        width: 80%;
        max-width: 1200px;
        animation: fadeIn 0.3s;
    }
    
    .apod-text-modal {
        background: rgba(0, 0, 0, 0.8);
        padding: 20px;
        border-radius: 10px;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .apod-close {
        color: #fff;
        float: right;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
        margin-bottom: 10px;
    }
    
    .apod-modal-content img {
        width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    
    .apod-modal-content h3 {
        color: #fff;
        margin-top: 15px;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    </style>
</section>

<!-- Seção de Mapa Interativo -->
<section class="featured-section">
    <h2 class="section-title">🛰️ Localização da ISS em Tempo Real</h2>
    <p class="section-description">Acompanhe a posição atual da Estação Espacial Internacional enquanto ela orbita a Terra a 27.600 km/h.</p>
    
    <div class="iss-tracker">
        <div class="iss-info">
            <div class="iss-data-item">
                <span class="iss-data-label">Latitude:</span>
                <span id="iss-lat" class="iss-data-value">Carregando...</span>
            </div>
            <div class="iss-data-item">
                <span class="iss-data-label">Longitude:</span>
                <span id="iss-lng" class="iss-data-value">Carregando...</span>
            </div>
            <div class="iss-data-item">
                <span class="iss-data-label">Velocidade:</span>
                <span id="iss-speed" class="iss-data-value">27.600 km/h</span>
            </div>
            <div class="iss-data-item">
                <span class="iss-data-label">Altitude:</span>
                <span id="iss-altitude" class="iss-data-value">408 km</span>
            </div>
        </div>
        
        <!-- Mapa da ISS -->
        <div id="iss-map" class="interactive-map"></div>
        
        <p class="iss-note">A Estação Espacial Internacional (ISS) completa uma órbita ao redor da Terra a cada 90 minutos.</p>
    </div>
    
    <!-- Adiciona o Leaflet para o mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    
    <script>
    // Função para inicializar o mapa da ISS
    function initISSMap() {
        // Inicializa o mapa Leaflet
        const map = L.map('iss-map').setView([0, 0], 2);
        
        // Adiciona o mapa base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);
        
        // Cria um ícone personalizado para a ISS
        const issIcon = L.icon({
            iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/International_Space_Station.svg/200px-International_Space_Station.svg.png',
            iconSize: [50, 30],
            iconAnchor: [25, 15]
        });
        
        // Adiciona o marcador da ISS
        const marker = L.marker([0, 0], {icon: issIcon}).addTo(map);
        
        // Adiciona o caminho da ISS
        const issPath = L.polyline([], {
            color: '#00f7ff',
            weight: 2,
            opacity: 0.7
        }).addTo(map);
        
        // Array para armazenar os pontos do caminho
        let issPathPoints = [];
        
        // Função para atualizar a posição da ISS
        function updateISSPosition() {
            // Usamos HTTPS para evitar problemas de mixed content
            fetch('https://api.wheretheiss.at/v1/satellites/25544')
                .then(response => response.json())
                .then(data => {
                    const lat = parseFloat(data.latitude);
                    const lng = parseFloat(data.longitude);
                    
                    // Atualiza o marcador e centraliza o mapa
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], map.getZoom() || 3);
                    
                    // Atualiza as informações de latitude e longitude
                    document.getElementById('iss-lat').innerText = lat.toFixed(4);
                    document.getElementById('iss-lng').innerText = lng.toFixed(4);
                    
                    // Adiciona o ponto atual ao rastro (limitado a 50 pontos)
                    issPathPoints.push([lat, lng]);
                    if (issPathPoints.length > 50) {
                        issPathPoints.shift(); // Remove o ponto mais antigo
                    }
                    
                    // Atualiza o caminho da ISS
                    issPath.setLatLngs(issPathPoints);
                    
                    console.log(`Posição da ISS atualizada: ${lat.toFixed(4)}, ${lng.toFixed(4)}`);
                })
                .catch(error => {
                    console.error('Erro ao obter posição da ISS:', error);
                    // Tenta usar a API alternativa se a primeira falhar
                    fetch('https://api.n2yo.com/rest/v1/satellite/positions/25544/0/0/0/1/&apiKey=DEMO_KEY')
                        .then(response => response.json())
                        .then(data => {
                            if (data.positions && data.positions.length > 0) {
                                const pos = data.positions[0];
                                const lat = parseFloat(pos.satlatitude);
                                const lng = parseFloat(pos.satlongitude);
                                
                                // Atualiza o marcador e centraliza o mapa
                                marker.setLatLng([lat, lng]);
                                map.setView([lat, lng], map.getZoom() || 3);
                                
                                // Atualiza as informações de latitude e longitude
                                document.getElementById('iss-lat').innerText = lat.toFixed(4);
                                document.getElementById('iss-lng').innerText = lng.toFixed(4);
                                
                                // Adiciona o ponto atual ao rastro
                                issPathPoints.push([lat, lng]);
                                if (issPathPoints.length > 50) {
                                    issPathPoints.shift();
                                }
                                
                                // Atualiza o caminho da ISS
                                issPath.setLatLngs(issPathPoints);
                            }
                        })
                        .catch(err => {
                            console.error('Erro ao usar API alternativa:', err);
                        });
                });
        }
        
        // Atualiza a posição inicialmente e a cada 5 segundos
        updateISSPosition();
        setInterval(updateISSPosition, 5000);
    }
    
    // Inicializa o mapa quando a página terminar de carregar
    document.addEventListener('DOMContentLoaded', initISSMap);
    </script>
    
    <style>
    .interactive-map {
        height: 400px;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 0 20px rgba(0, 247, 255, 0.3);
    }
    
    .iss-info {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }
    
    .iss-data-item {
        background: rgba(0, 0, 0, 0.5);
        padding: 10px 15px;
        border-radius: 5px;
        flex: 1;
        min-width: 150px;
        text-align: center;
    }
    
    .iss-data-label {
        display: block;
        color: #aaa;
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .iss-data-value {
        display: block;
        color: #4da6ff;
        font-size: 18px;
        font-weight: bold;
        font-family: 'Orbitron', sans-serif;
    }
    
    .iss-note {
        text-align: center;
        color: #aaa;
        font-style: italic;
        margin-top: 15px;
    }
    </style>
</section>

<?php
// Renderiza o rodapé
render_footer();
?>
