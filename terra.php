<?php
/**
 * SatéliteVision - Página da Terra
 * Visualização de imagens da Terra capturadas por satélites
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Terra', 'Visualização de imagens da Terra capturadas por satélites em tempo real');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Planeta Terra</h1>
    <p class="hero-subtitle">Explore nosso planeta azul através de imagens capturadas por satélites em tempo real. Observe a Terra de diferentes perspectivas e acompanhe eventos naturais.</p>
</section>

<!-- Seção EPIC (Earth Polychromatic Imaging Camera) -->
<section id="epic" class="featured-section">
    <h2 class="section-title">🌍 Última imagem da Terra (NASA EPIC)</h2>
    <p class="section-description">A câmera EPIC (Earth Polychromatic Imaging Camera) a bordo do satélite DSCOVR captura imagens incríveis da Terra inteira a partir de um ponto entre a Terra e o Sol.</p>
    
    <div class="epic-container">
        <img id="epic-img" src="img/fallback-earth.jpg" alt="Imagem EPIC" style="width:100%; max-width:800px; border-radius: 10px;">
        <p id="epic-caption" class="epic-caption">Carregando imagem da Terra...</p>
    </div>
    
    <script>
    fetch('api/epic.php')
      .then(res => res.json())
      .then(data => {
        if (!data.error) {
          document.getElementById('epic-img').src = data.url;
          document.getElementById('epic-caption').innerText = `${data.caption} (${data.date})`;
        } else {
          document.getElementById('epic-caption').innerText = "Erro ao carregar imagem da NASA. Usando imagem de fallback.";
        }
      })
      .catch(err => {
        console.error("Erro ao buscar dados EPIC:", err);
        document.getElementById('epic-caption').innerText = "Erro ao conectar com a API. Usando imagem de fallback.";
      });
    </script>
</section>

<!-- Seção de Eventos Naturais -->
<section id="events" class="featured-section">
    <h2 class="section-title">Eventos Naturais</h2>
    <p class="section-description">Acompanhe eventos naturais detectados por satélites ao redor do mundo, como incêndios, erupções vulcânicas, tempestades e mais. Dados fornecidos pela NASA EONET (Earth Observatory Natural Event Tracker).</p>
    
    <div id="earth-events" class="events-grid">
        <?php render_loader('Carregando eventos naturais da NASA EONET...'); ?>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Carrega os eventos naturais da API EONET da NASA
        fetch('api/eonet.php')
            .then(response => response.json())
            .then(data => {
                const eventsContainer = document.getElementById('earth-events');
                
                if (data.success && data.events && data.events.length > 0) {
                    // Limpa o container
                    eventsContainer.innerHTML = '';
                    
                    // Adiciona cada evento
                    data.events.forEach(event => {
                        const eventCard = document.createElement('div');
                        eventCard.className = 'event-card';
                        eventCard.style.borderLeft = `4px solid ${event.color}`;
                        
                        // Conteúdo do card
                        eventCard.innerHTML = `
                            <div class="event-icon" style="background-color: ${event.color}">
                                <i class="fas ${event.icon}"></i>
                            </div>
                            <div class="event-info">
                                <h3>${event.title}</h3>
                                <p class="event-category">${event.category}</p>
                                <p class="event-date">${event.date}</p>
                                ${event.description ? `<p class="event-description">${event.description}</p>` : ''}
                                ${event.sources && event.sources.length > 0 ? 
                                    `<a href="${event.sources[0].url}" target="_blank" class="event-source">Fonte: ${event.sources[0].id}</a>` : ''}
                            </div>
                        `;
                        
                        eventsContainer.appendChild(eventCard);
                    });
                    
                    // Adiciona informação sobre o total de eventos
                    const totalInfo = document.createElement('div');
                    totalInfo.className = 'events-total';
                    totalInfo.innerHTML = `<p>Total de ${data.total} eventos ativos monitorados pela NASA</p>`;
                    eventsContainer.appendChild(totalInfo);
                } else {
                    // Mensagem de fallback
                    eventsContainer.innerHTML = `
                        <div class="events-empty">
                            <i class="fas fa-satellite-dish"></i>
                            <p>${data.message || 'Não foi possível carregar os eventos naturais neste momento.'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erro ao carregar eventos naturais:', error);
                document.getElementById('earth-events').innerHTML = `
                    <div class="events-empty">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Erro ao conectar com a API de eventos naturais.</p>
                    </div>
                `;
            });
    });
    </script>
</section>

<!-- Seção GOES -->
<section id="goes" class="featured-section">
    <h2 class="section-title">Satélite GOES</h2>
    <p class="section-description">Os satélites GOES (Geostationary Operational Environmental Satellite) fornecem monitoramento contínuo e imagens de alta resolução para previsão do tempo e rastreamento de tempestades. Imagens atualizadas em tempo real da NOAA.</p>
    
    <div class="goes-viewer">
        <div class="goes-controls">
            <div class="goes-control-group">
                <label for="goes-date">Data:</label>
                <input type="date" id="goes-date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="goes-control-group">
                <label for="goes-type">Tipo de Imagem:</label>
                <select id="goes-type">
                    <option value="geocolor">GeoColor</option>
                    <option value="band13">Infravermelho</option>
                    <option value="band02">Visível</option>
                    <option value="band14">Vapor de Água</option>
                </select>
            </div>
            <div class="goes-control-group">
                <label for="goes-region">Região:</label>
                <select id="goes-region">
                    <option value="full_disk">Disco Completo</option>
                    <option value="conus">América do Norte</option>
                    <option value="mesoscale_01">Mesoscala 1</option>
                    <option value="mesoscale_02">Mesoscala 2</option>
                </select>
            </div>
            <button id="goes-update" class="btn-view">Atualizar <i class="fas fa-sync"></i></button>
        </div>
        
        <div class="goes-image-container">
            <img id="goes-image" src="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/FD/GEOCOLOR/latest.jpg" alt="Imagem do satélite GOES" style="max-width:100%; height:auto;">
            <div class="goes-timestamp">Última atualização: <span id="goes-timestamp"><?php echo date('d/m/Y H:i:s'); ?></span></div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Carrega a imagem inicial do GOES
        loadGOESImage();
        
        // Configura o evento de clique no botão de atualizar
        document.getElementById('goes-update').addEventListener('click', loadGOESImage);
        
        // Função para carregar a imagem do GOES
        function loadGOESImage() {
            const date = document.getElementById('goes-date').value;
            const type = document.getElementById('goes-type').value;
            const region = document.getElementById('goes-region').value;
            
            // Mostra um indicador de carregamento
            const goesImage = document.getElementById('goes-image');
            goesImage.style.opacity = '0.5';
            
            // Faz a requisição para a API
            fetch(`api/goes.php?date=${date}&type=${type}&region=${region}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza a imagem
                        goesImage.src = data.url;
                        document.getElementById('goes-timestamp').textContent = data.timestamp;
                        
                        // Restaura a opacidade
                        goesImage.onload = function() {
                            goesImage.style.opacity = '1';
                        };
                    } else {
                        // Exibe mensagem de erro
                        console.error('Erro ao carregar imagem GOES:', data.message);
                        document.getElementById('goes-timestamp').textContent = 'Erro ao carregar imagem. ' + data.message;
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição GOES:', error);
                    document.getElementById('goes-timestamp').textContent = 'Erro na conexão com a API GOES.';
                    goesImage.style.opacity = '1';
                });
        }
    });
    </script>
</section>

<!-- Seção Landsat -->
<section id="landsat" class="featured-section">
    <h2 class="section-title">Landsat 8</h2>
    <p class="section-description">O satélite Landsat 8 captura imagens de alta resolução da superfície terrestre, permitindo monitorar mudanças ambientais, uso do solo e recursos naturais. Acesse imagens da NASA Earth Observation.</p>
    
    <div class="landsat-viewer">
        <div class="landsat-search">
            <div class="landsat-search-group">
                <label for="landsat-location">Localização:</label>
                <input type="text" id="landsat-location" placeholder="Ex: São Paulo, Brasil">
            </div>
            <div class="landsat-search-group">
                <label for="landsat-date">Data:</label>
                <input type="date" id="landsat-date" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>" max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="landsat-search-group">
                <label for="landsat-cloud">Cobertura de Nuvens (máx %):</label>
                <input type="range" id="landsat-cloud" min="0" max="100" value="20" step="5">
                <span id="landsat-cloud-value">20%</span>
            </div>
            <button id="landsat-search-btn" class="btn-view">Buscar <i class="fas fa-search"></i></button>
        </div>
        
        <div id="landsat-results" class="landsat-results">
            <div class="landsat-placeholder">
                <i class="fas fa-satellite"></i>
                <p>Busque uma localização para ver imagens do Landsat 8</p>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Atualiza o valor exibido do slider de nuvens
        const cloudSlider = document.getElementById('landsat-cloud');
        const cloudValue = document.getElementById('landsat-cloud-value');
        
        cloudSlider.addEventListener('input', function() {
            cloudValue.textContent = this.value + '%';
        });
        
        // Configura o evento de clique no botão de busca
        document.getElementById('landsat-search-btn').addEventListener('click', searchLandsat);
        
        // Função para buscar imagens do Landsat
        function searchLandsat() {
            const location = document.getElementById('landsat-location').value;
            const date = document.getElementById('landsat-date').value;
            const cloudCover = document.getElementById('landsat-cloud').value;
            
            // Valida a localização
            if (!location) {
                alert('Por favor, informe uma localização para buscar.');
                return;
            }
            
            // Mostra um indicador de carregamento
            const resultsContainer = document.getElementById('landsat-results');
            resultsContainer.innerHTML = `
                <div class="landsat-loading">
                    <div class="spinner"></div>
                    <p>Buscando imagens do Landsat 8 para ${location}...</p>
                </div>
            `;
            
            // Faz a requisição para a API
            fetch(`api/landsat.php?location=${encodeURIComponent(location)}&date=${date}&cloud_cover=${cloudCover}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Exibe a imagem encontrada
                        resultsContainer.innerHTML = `
                            <div class="landsat-image-container">
                                <img src="${data.url}" alt="Imagem Landsat de ${data.location.name}" class="landsat-image" style="max-width:100%; height:auto; max-height:70vh; object-fit:contain; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.2); display:block; margin:0 auto;">
                                <div class="landsat-image-info">
                                    <h3>${data.location.name}</h3>
                                    <p>Data da imagem: ${data.date}</p>
                                    <p>Coordenadas: ${data.location.lat}, ${data.location.lon}</p>
                                    ${data.cloud_score ? `<p>Cobertura de nuvens: ${Math.round(data.cloud_score * 100)}%</p>` : ''}
                                    ${data.note ? `<p class="landsat-note">${data.note}</p>` : ''}
                                </div>
                            </div>
                        `;
                    } else {
                        // Exibe mensagem de erro
                        resultsContainer.innerHTML = `
                            <div class="landsat-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <p>${data.message || 'Não foi possível encontrar imagens para esta localização e data.'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição Landsat:', error);
                    resultsContainer.innerHTML = `
                        <div class="landsat-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Erro na conexão com a API Landsat.</p>
                        </div>
                    `;
                });
        }
    });
    </script>
</section>

<!-- Seção de Mapa Interativo -->
<section class="featured-section">
    <h2 class="section-title">Mapa Global</h2>
    <p class="section-description">Explore a Terra em um mapa interativo com camadas de dados de satélites da NASA e NOAA.</p>
    
    <div class="map-controls">
        <div class="map-control-group">
            <label for="map-layer">Camada:</label>
            <select id="map-layer">
                <option value="blue_marble">Blue Marble (NASA)</option>
                <option value="land_surface">Temperatura da Superfície</option>
                <option value="cloud_fraction">Cobertura de Nuvens</option>
                <option value="sea_surface">Temperatura do Mar</option>
            </select>
        </div>
        <button id="map-update" class="btn-view">Atualizar Camada <i class="fas fa-layer-group"></i></button>
    </div>
    
    <div id="map-container" class="map-container" style="margin-top: 20px; text-align: center; min-height: 300px;">
        <!-- O conteúdo será carregado dinamicamente pelo JavaScript -->
        <div class="map-loading">
            <div class="loader">
                <div class="loader-ring"></div>
                <div class="loader-planet"></div>
            </div>
            <p>Carregando mapa global...</p>
        </div>
    </div>
    
    <!-- O script para controlar o mapa está no final da página -->
    
</section>

<!-- Scripts específicos da página -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atualiza o valor do slider de nuvens
    const cloudSlider = document.getElementById('landsat-cloud');
    const cloudValue = document.getElementById('landsat-cloud-value');
    
    if (cloudSlider && cloudValue) {
        cloudSlider.addEventListener('input', function() {
            cloudValue.textContent = this.value + '%';
        });
    }
    
    // Controles do GOES
    const goesUpdateBtn = document.getElementById('goes-update');
    
    if (goesUpdateBtn) {
        goesUpdateBtn.addEventListener('click', function loadGOESImage() {
            const date = document.getElementById('goes-date').value;
            const type = document.getElementById('goes-type').value;
            const region = document.getElementById('goes-region').value;
            
            // Mostra um loader enquanto carrega a imagem
            document.getElementById('goes-image').src = 'img/loading-earth.gif';
            
            // Faz a requisição para a API do GOES
            fetch(`api/goes.php?date=${date}&type=${type}&region=${region}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('goes-image').src = data.url;
                        document.getElementById('goes-timestamp').textContent = data.timestamp;
                    } else {
                        document.getElementById('goes-image').src = 'img/fallback-earth.jpg';
                        alert('Erro ao carregar imagem: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar imagem GOES:', error);
                    document.getElementById('goes-image').src = 'img/fallback-earth.jpg';
                    alert('Erro ao conectar com a API GOES');
                });
        });
    }
    
    // Busca do Landsat
    const landsatSearchBtn = document.getElementById('landsat-search-btn');
    
    if (landsatSearchBtn) {
        landsatSearchBtn.addEventListener('click', function() {
            const location = document.getElementById('landsat-location').value;
            const date = document.getElementById('landsat-date').value;
            const cloud = document.getElementById('landsat-cloud').value;
            
            if (!location) {
                alert('Por favor, informe uma localização');
                return;
            }
            
            // Mostra o loader enquanto carrega
            const resultsContainer = document.getElementById('landsat-results');
            
            resultsContainer.innerHTML = `
                <div class="landsat-loading">
                    <div class="loader">
                        <div class="loader-ring"></div>
                        <div class="loader-planet"></div>
                    </div>
                    <p>Buscando imagens para ${location}...</p>
                </div>
            `;
            
            // Faz a requisição para a API do Landsat
            fetch(`api/landsat.php?location=${encodeURIComponent(location)}&date=${date}&cloud_cover=${cloud}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Limpa o container
                        resultsContainer.innerHTML = '';
                        
                        // Cria o elemento de resultado
                        const resultItem = document.createElement('div');
                        resultItem.className = 'landsat-result-item';
                        
                        // Adiciona a imagem e informações
                        resultItem.innerHTML = `
                            <div class="landsat-result-image">
                                <img src="${data.url}" alt="Imagem Landsat de ${location}" style="max-width:100%; height:auto;">
                            </div>
                            <div class="landsat-result-info">
                                <h4>${data.location.name || location}</h4>
                                <p>Data: ${data.date}</p>
                                ${data.cloud_score ? `<p>Cobertura de nuvens: ${Math.round(data.cloud_score * 100)}%</p>` : ''}
                                ${data.id ? `<p>ID: ${data.id}</p>` : ''}
                                ${data.note ? `<p><em>${data.note}</em></p>` : ''}
                                <button class="btn-view landsat-fullview">Ver em tamanho completo <i class="fas fa-expand"></i></button>
                            </div>
                        `;
                        
                        resultsContainer.appendChild(resultItem);
                        
                        // Adiciona evento de clique na imagem e no botão
                        const img = resultItem.querySelector('.landsat-result-image img');
                        img.addEventListener('click', function() {
                            createLightbox(this.src, this.alt);
                        });
                        
                        const viewBtn = resultItem.querySelector('.landsat-fullview');
                        viewBtn.addEventListener('click', function() {
                            createLightbox(img.src, img.alt);
                        });
                    } else {
                        resultsContainer.innerHTML = `
                            <div class="landsat-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <p>${data.message || 'Erro ao buscar imagens do Landsat'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar imagens Landsat:', error);
                    resultsContainer.innerHTML = `
                        <div class="landsat-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Erro ao conectar com a API Landsat</p>
                        </div>
                    `;
                });
        });
    }
    
    // Controles do mapa
    const mapUpdateBtn = document.getElementById('map-update');
    const mapContainer = document.getElementById('map-container');
    
    if (mapUpdateBtn && mapContainer) {
        // Inicializa o mapa com a camada padrão
        updateMapLayer('blue_marble');
        
        // Adiciona evento de clique no botão de atualizar
        mapUpdateBtn.addEventListener('click', function() {
            const layer = document.getElementById('map-layer').value;
            updateMapLayer(layer);
        });
        
        // Função para atualizar a camada do mapa
        function updateMapLayer(layer) {
            // Define as URLs das diferentes camadas
            const layerUrls = {
                'blue_marble': 'https://neo.gsfc.nasa.gov/wms/wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&LAYERS=BlueMarbleNG-TB&WIDTH=1024&HEIGHT=512&CRS=EPSG:4326&STYLES=&BBOX=-90,-180,90,180',
                'land_surface': 'https://neo.gsfc.nasa.gov/wms/wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&LAYERS=MOD_LSTD_CLIM_M&WIDTH=1024&HEIGHT=512&CRS=EPSG:4326&STYLES=&BBOX=-90,-180,90,180',
                'cloud_fraction': 'https://neo.gsfc.nasa.gov/wms/wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&LAYERS=MODAL2_M_CLD_FR&WIDTH=1024&HEIGHT=512&CRS=EPSG:4326&STYLES=&BBOX=-90,-180,90,180',
                'sea_surface': 'https://neo.gsfc.nasa.gov/wms/wms?SERVICE=WMS&VERSION=1.3.0&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&LAYERS=AVHRR_SST_M&WIDTH=1024&HEIGHT=512&CRS=EPSG:4326&STYLES=&BBOX=-90,-180,90,180'
            };
            
            // Mostra um loader enquanto carrega
            mapContainer.innerHTML = `
                <div class="map-loading">
                    <div class="loader">
                        <div class="loader-ring"></div>
                        <div class="loader-planet"></div>
                    </div>
                    <p>Carregando camada do mapa...</p>
                </div>
            `;
            
            // Cria uma nova imagem
            const img = new Image();
            img.src = layerUrls[layer] || layerUrls['blue_marble'];
            img.alt = 'Mapa Global';
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.style.borderRadius = '8px';
            img.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
            
            // Quando a imagem carregar, adiciona ao container
            img.onload = function() {
                mapContainer.innerHTML = '';
                mapContainer.appendChild(img);
                
                // Adiciona legenda
                const legend = document.createElement('div');
                legend.className = 'map-legend';
                
                // Define as legendas para cada camada
                const legends = {
                    'blue_marble': 'Imagem Blue Marble da NASA - Visão natural da Terra',
                    'land_surface': 'Temperatura da superfície terrestre - Dados MODIS',
                    'cloud_fraction': 'Fração de cobertura de nuvens - Dados MODIS',
                    'sea_surface': 'Temperatura da superfície do mar - Dados AVHRR'
                };
                
                legend.innerHTML = `<p>${legends[layer] || legends['blue_marble']}</p>`;
                mapContainer.appendChild(legend);
            };
            
            // Em caso de erro no carregamento
            img.onerror = function() {
                mapContainer.innerHTML = `
                    <div class="map-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Erro ao carregar a camada do mapa</p>
                    </div>
                `;
            };
        }
    }
});
</script>

<?php
// Renderiza o rodapé
render_footer();
?>
