<?php
/**
 * SatéliteVision - Página da ISS (Parte 4)
 * Scripts e rodapé da página da ISS
 */

// Esta parte deve ser incluída após iss_parte3.php
?>

<!-- Seção de Avistamentos -->
<section class="featured-section">
    <h2 class="section-title">Aviste a ISS</h2>
    <p class="section-description">A ISS é visível a olho nu da Terra. Descubra quando ela passará sobre sua localização.</p>
    
    <div class="iss-sighting-form">
        <div class="form-group">
            <label for="sighting-location">Sua Localização:</label>
            <input type="text" id="sighting-location" placeholder="Ex: São Paulo, Brasil">
        </div>
        <button id="sighting-search" class="btn btn-primary">Buscar Passagens <i class="fas fa-search"></i></button>
    </div>
    
    <div id="sighting-results" class="sighting-results">
        <div class="sighting-placeholder">
            <i class="fas fa-satellite"></i>
            <p>Informe sua localização para ver quando a ISS será visível em seu céu</p>
        </div>
    </div>
</section>

<!-- Scripts específicos da página -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o mapa da ISS
    let issMap = null;
    let issMarker = null;
    let issTrack = [];
    
    function initISSMap() {
        // Cria o mapa
        issMap = L.map('iss-map').setView([0, 0], 2);
        
        // Adiciona o tile layer com estilo escuro
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(issMap);
        
        // Cria um ícone personalizado para a ISS
        const issIcon = L.divIcon({
            className: 'iss-icon',
            html: '<i class="fas fa-space-shuttle"></i>',
            iconSize: [30, 30]
        });
        
        // Adiciona o marcador da ISS
        issMarker = L.marker([0, 0], {icon: issIcon}).addTo(issMap);
        
        // Cria a linha para o rastro da ISS
        issTrack = [];
        
        // Obtém a localização inicial da ISS
        updateISSLocation();
        
        // Atualiza a cada 5 segundos
        setInterval(updateISSLocation, 5000);
    }
    
    function updateISSLocation() {
        fetch('api/iss_location.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro ao obter localização da ISS:', data.error);
                    return;
                }
                
                const lat = parseFloat(data.iss_position.latitude);
                const lng = parseFloat(data.iss_position.longitude);
                
                // Atualiza o marcador
                issMarker.setLatLng([lat, lng]);
                
                // Adiciona ponto ao rastro
                issTrack.push([lat, lng]);
                
                // Limita o tamanho do rastro
                if (issTrack.length > 50) {
                    issTrack.shift();
                }
                
                // Atualiza o rastro no mapa
                if (window.issPathLine) {
                    issMap.removeLayer(window.issPathLine);
                }
                
                window.issPathLine = L.polyline(issTrack, {
                    color: '#00b4ff',
                    weight: 2,
                    opacity: 0.7
                }).addTo(issMap);
                
                // Atualiza as informações da ISS
                document.getElementById('iss-lat').textContent = lat.toFixed(4);
                document.getElementById('iss-lng').textContent = lng.toFixed(4);
                document.getElementById('iss-update-time').textContent = new Date().toLocaleString('pt-BR');
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }
    
    // Inicializa o mapa quando a página carregar
    initISSMap();
    
    // Botões de controle do mapa
    const refreshButton = document.getElementById('iss-refresh');
    if (refreshButton) {
        refreshButton.addEventListener('click', updateISSLocation);
    }
    
    const centerButton = document.getElementById('iss-center');
    if (centerButton) {
        centerButton.addEventListener('click', function() {
            if (issMarker) {
                issMap.setView(issMarker.getLatLng(), 3);
            }
        });
    }
    
    // Controles do feed ao vivo
    const feedHDButton = document.getElementById('feed-hd');
    if (feedHDButton) {
        feedHDButton.addEventListener('click', function() {
            const iframe = document.getElementById('iss-live-feed');
            const currentSrc = iframe.src;
            
            if (currentSrc.includes('&hd=1')) {
                iframe.src = currentSrc.replace('&hd=1', '');
                this.innerHTML = 'HD <i class="fas fa-hd"></i>';
            } else {
                iframe.src = currentSrc + '&hd=1';
                this.innerHTML = 'SD <i class="fas fa-video"></i>';
            }
        });
    }
    
    const feedMuteButton = document.getElementById('feed-mute');
    if (feedMuteButton) {
        feedMuteButton.addEventListener('click', function() {
            const iframe = document.getElementById('iss-live-feed');
            const currentSrc = iframe.src;
            
            if (currentSrc.includes('&mute=1')) {
                iframe.src = currentSrc.replace('&mute=1', '');
                this.innerHTML = 'Mudo <i class="fas fa-volume-mute"></i>';
            } else {
                iframe.src = currentSrc + '&mute=1';
                this.innerHTML = 'Som <i class="fas fa-volume-up"></i>';
            }
        });
    }
    
    const feedFullscreenButton = document.getElementById('feed-fullscreen');
    if (feedFullscreenButton) {
        feedFullscreenButton.addEventListener('click', function() {
            const iframe = document.getElementById('iss-live-feed');
            
            if (iframe.requestFullscreen) {
                iframe.requestFullscreen();
            } else if (iframe.mozRequestFullScreen) {
                iframe.mozRequestFullScreen();
            } else if (iframe.webkitRequestFullscreen) {
                iframe.webkitRequestFullscreen();
            } else if (iframe.msRequestFullscreen) {
                iframe.msRequestFullscreen();
            }
        });
    }
    
    // Busca de avistamentos da ISS
    const sightingSearchButton = document.getElementById('sighting-search');
    if (sightingSearchButton) {
        sightingSearchButton.addEventListener('click', function() {
            const location = document.getElementById('sighting-location').value;
            
            if (!location) {
                alert('Por favor, informe sua localização');
                return;
            }
            
            const resultsContainer = document.getElementById('sighting-results');
            
            resultsContainer.innerHTML = `
                <div class="loading-effect">
                    <div class="loader">
                        <div class="loader-ring"></div>
                        <div class="loader-planet"></div>
                    </div>
                    <p>Buscando passagens da ISS sobre ${location}...</p>
                </div>
            `;
            
            // Simula o tempo de carregamento
            setTimeout(function() {
                // Gera dados simulados de avistamentos
                const today = new Date();
                const sightings = [];
                
                for (let i = 1; i <= 5; i++) {
                    const sightingDate = new Date(today);
                    sightingDate.setDate(today.getDate() + i);
                    
                    const hour = Math.floor(Math.random() * 24);
                    const minute = Math.floor(Math.random() * 60);
                    sightingDate.setHours(hour, minute, 0);
                    
                    const duration = Math.floor(Math.random() * 6) + 2;
                    const maxElevation = Math.floor(Math.random() * 70) + 20;
                    const approach = ['NE', 'NO', 'SE', 'SO'][Math.floor(Math.random() * 4)];
                    const departure = ['NO', 'NE', 'SO', 'SE'][Math.floor(Math.random() * 4)];
                    
                    sightings.push({
                        date: sightingDate,
                        duration: duration,
                        maxElevation: maxElevation,
                        approach: approach,
                        departure: departure
                    });
                }
                
                // Ordena por data
                sightings.sort((a, b) => a.date - b.date);
                
                // Renderiza os resultados
                let html = '<h3>Próximas passagens da ISS sobre ' + location + '</h3>';
                html += '<div class="sighting-list">';
                
                sightings.forEach(sighting => {
                    const dateStr = sighting.date.toLocaleDateString('pt-BR');
                    const timeStr = sighting.date.toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'});
                    
                    html += `
                        <div class="sighting-item">
                            <div class="sighting-date">
                                <div class="sighting-day">${dateStr}</div>
                                <div class="sighting-time">${timeStr}</div>
                            </div>
                            <div class="sighting-details">
                                <div class="sighting-detail">
                                    <span class="detail-label">Duração:</span>
                                    <span class="detail-value">${sighting.duration} minutos</span>
                                </div>
                                <div class="sighting-detail">
                                    <span class="detail-label">Elevação máxima:</span>
                                    <span class="detail-value">${sighting.maxElevation}°</span>
                                </div>
                                <div class="sighting-detail">
                                    <span class="detail-label">Aparece:</span>
                                    <span class="detail-value">${sighting.approach}</span>
                                </div>
                                <div class="sighting-detail">
                                    <span class="detail-label">Desaparece:</span>
                                    <span class="detail-value">${sighting.departure}</span>
                                </div>
                            </div>
                            <div class="sighting-visibility ${sighting.maxElevation > 40 ? 'high' : 'medium'}">
                                ${sighting.maxElevation > 40 ? 'Visibilidade Alta' : 'Visibilidade Média'}
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                html += '<p class="sighting-note">A ISS aparece como um ponto brilhante e rápido movendo-se pelo céu, semelhante a uma estrela em movimento.</p>';
                
                resultsContainer.innerHTML = html;
            }, 2000);
        });
    }
});
</script>

<?php
// Renderiza o rodapé
render_footer();
?>
