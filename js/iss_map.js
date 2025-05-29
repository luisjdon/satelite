/**
 * SatéliteVision - Mapa da ISS
 * Funções para gerenciar o mapa e a posição da ISS
 */

// Variáveis globais para o mapa
let issMap = null;
let issMarker = null;
let issPath = null;
let issPathPoints = [];

/**
 * Inicializa o mapa da ISS
 */
function initISSMap() {
    // Verifica se o elemento do mapa existe
    const mapElement = document.getElementById('iss-map');
    if (!mapElement) return;
    
    // Inicializa o mapa Leaflet
    issMap = L.map('iss-map').setView([0, 0], 2);
    
    // Adiciona o mapa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(issMap);
    
    // Adiciona o caminho da ISS
    issPath = L.polyline([], {
        color: '#00f7ff',
        weight: 2,
        opacity: 0.7
    }).addTo(issMap);
    
    // Inicializa o array de pontos do caminho
    issPathPoints = [];
    
    console.log('Mapa da ISS inicializado com sucesso!');
}

/**
 * Atualiza a posição da ISS no mapa
 * 
 * @param {number} lat Latitude
 * @param {number} lng Longitude
 */
function updateISSPosition(lat, lng) {
    if (!issMap) return;
    
    // Atualiza o marcador da ISS
    if (issMarker) {
        issMarker.setLatLng([lat, lng]);
    } else {
        // Cria um ícone personalizado para a ISS
        const issIcon = L.divIcon({
            className: 'iss-icon',
            html: '<i class="fas fa-satellite"></i>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        // Adiciona o marcador ao mapa
        issMarker = L.marker([lat, lng], {
            icon: issIcon
        }).addTo(issMap);
        
        // Adiciona um popup ao marcador
        issMarker.bindPopup(`
            <div class="iss-popup">
                <h3>Estação Espacial Internacional</h3>
                <p>Velocidade: 27.600 km/h</p>
                <p>Altitude: 408 km</p>
                <p>Órbita: 92 minutos</p>
            </div>
        `);
    }
    
    // Centraliza o mapa na ISS
    issMap.setView([lat, lng], issMap.getZoom() || 3);
    
    // Adiciona o ponto atual ao rastro (limitado a 50 pontos)
    issPathPoints.push([lat, lng]);
    if (issPathPoints.length > 50) {
        issPathPoints.shift(); // Remove o ponto mais antigo
    }
    
    // Atualiza o caminho da ISS
    issPath.setLatLngs(issPathPoints);
    
    console.log(`Posição da ISS atualizada: ${lat.toFixed(4)}, ${lng.toFixed(4)}`);
}

/**
 * Inicia o rastreamento da ISS
 */
function startISSTracking() {
    // Carrega a localização inicial da ISS
    loadISSLocation();
}
