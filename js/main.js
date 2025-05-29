/**
 * SatéliteVision - Script Principal
 * Funções para interatividade do site futurístico
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializa todos os componentes
    initNavigation();
    initNotifications();
    initImageGallery();
    initSatelliteData();
    
    // Adiciona classes de animação aos elementos que entram na viewport
    initScrollAnimations();
});

/**
 * Inicializa a navegação responsiva
 */
function initNavigation() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            this.innerHTML = mainNav.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : 
                '<i class="fas fa-bars"></i>';
        });
        
        // Fecha o menu ao clicar em um link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    mainNav.classList.remove('active');
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        });
    }
}

/**
 * Inicializa o sistema de notificações
 */
function initNotifications() {
    const notifications = document.querySelectorAll('.notification');
    
    notifications.forEach(notification => {
        const closeBtn = notification.querySelector('.notification-close');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 300);
            });
        }
        
        // Auto-fecha após 5 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }, 5000);
    });
}

/**
 * Inicializa a galeria de imagens com lightbox
 */
function initImageGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    galleryItems.forEach(item => {
        const image = item.querySelector('img');
        
        if (image) {
            item.addEventListener('click', function() {
                createLightbox(image.src, image.alt);
            });
        }
    });
}

/**
 * Cria um lightbox para visualização de imagens
 * 
 * @param {string} src URL da imagem
 * @param {string} alt Texto alternativo
 */
function createLightbox(src, alt) {
    // Remove qualquer lightbox existente
    const existingLightbox = document.querySelector('.lightbox');
    if (existingLightbox) {
        existingLightbox.remove();
    }
    
    // Cria o lightbox
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    
    // Adiciona o conteúdo
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <span class="lightbox-close"><i class="fas fa-times"></i></span>
            <img src="${src}" alt="${alt}">
            <div class="lightbox-caption">${alt}</div>
        </div>
    `;
    
    // Adiciona ao body
    document.body.appendChild(lightbox);
    
    // Impede o scroll do body
    document.body.style.overflow = 'hidden';
    
    // Anima a entrada
    setTimeout(() => {
        lightbox.style.opacity = '1';
    }, 10);
    
    // Configura o evento de fechar
    const closeBtn = lightbox.querySelector('.lightbox-close');
    closeBtn.addEventListener('click', closeLightbox);
    
    // Fecha ao clicar fora da imagem
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
    
    // Fecha com a tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
}

/**
 * Fecha o lightbox
 */
function closeLightbox() {
    const lightbox = document.querySelector('.lightbox');
    
    if (lightbox) {
        lightbox.style.opacity = '0';
        setTimeout(() => {
            lightbox.remove();
            document.body.style.overflow = '';
        }, 300);
    }
}

/**
 * Inicializa o mapa interativo
 * 
 * @param {string} id ID do elemento do mapa
 * @param {number} lat Latitude inicial
 * @param {number} lng Longitude inicial
 * @param {number} zoom Nível de zoom inicial
 */
function initMap(id, lat, lng, zoom) {
    const mapElement = document.getElementById(id);
    
    if (!mapElement) return;
    
    // Verifica se a API do Leaflet está disponível
    if (typeof L !== 'undefined') {
        // Cria o mapa
        const map = L.map(id).setView([lat, lng], zoom);
        
        // Adiciona o tile layer com estilo escuro
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
        
        // Adiciona a localização da ISS se estiver na página ISS
        if (id === 'iss-map') {
            updateISSLocation(map);
            // Atualiza a cada 5 segundos
            setInterval(() => updateISSLocation(map), 5000);
        }
        
        return map;
    } else {
        console.error('Leaflet não está disponível');
        mapElement.innerHTML = '<div class="error-message">Não foi possível carregar o mapa</div>';
    }
}

/**
 * Carrega a localização da ISS
 */
function loadISSLocation() {
    // Mostra indicador de carregamento
    const issLocationContainer = document.getElementById('iss-location');
    if (issLocationContainer) {
        issLocationContainer.innerHTML = '<div class="loading-container"><div class="loader"><div class="loader-ring"></div><div class="loader-planet"></div></div><p>Carregando localização da ISS...</p></div>';
    }
    
    // Usa a nova API de imagens diretas que sempre funciona
    fetch('api/imagens_diretas.php?type=iss')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta da API: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (issLocationContainer && data.iss_data && data.iss_data.iss_position) {
                // Inicializa o mapa se ainda não foi inicializado
                if (!issMap) {
                    initISSMap();
                }
                
                // Atualiza a posição da ISS no mapa
                const lat = parseFloat(data.iss_data.iss_position.latitude);
                const lng = parseFloat(data.iss_data.iss_position.longitude);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    updateISSPosition(lat, lng);
                    
                    // Atualiza as informações adicionais
                    if (data.iss_data.info) {
                        updateISSInfo(lat, lng, data.iss_data.info);
                    } else {
                        updateISSInfo(lat, lng);
                    }
                    
                    // Remove o indicador de carregamento
                    issLocationContainer.querySelector('.loading-container')?.remove();
                    console.log('Localização da ISS carregada com sucesso!');
                    
                    // Atualiza a localização a cada 10 segundos
                    setTimeout(loadISSLocation, 10000);
                }
            } else {
                throw new Error('Dados da ISS inválidos ou ausentes');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar localização da ISS:', error);
            
            // Em caso de erro, exibe mensagem de erro e tenta novamente após 30 segundos
            if (issLocationContainer) {
                issLocationContainer.innerHTML = `
                    <div class="error-container">
                        <p>Não foi possível carregar a localização da ISS. Tentando novamente em 30 segundos...</p>
                    </div>
                `;
                
                // Tenta novamente após 30 segundos
                setTimeout(loadISSLocation, 30000);
            }
        });
}

/**
 * Simula a localização da ISS quando a API não responde
 * 
 * @param {object} map Objeto do mapa Leaflet
 */
function simulateISSLocation(map) {
    // Gera uma localização aleatória para a ISS
    const lat = Math.random() * 160 - 80; // -80 a 80
    const lng = Math.random() * 360 - 180; // -180 a 180
    
    // Atualiza o marcador da ISS
    if (window.issMarker) {
        window.issMarker.setLatLng([lat, lng]);
    } else {
        // Cria um ícone personalizado para a ISS
        const issIcon = L.divIcon({
            className: 'iss-icon',
            html: '<i class="fas fa-satellite"></i>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        // Adiciona o marcador ao mapa
        window.issMarker = L.marker([lat, lng], {
            icon: issIcon
        }).addTo(map);
        
        // Centraliza o mapa na ISS
        map.setView([lat, lng], 3);
    }
    
    // Informações simuladas da ISS
    const issInfo = {
        altitude: '408 km',
        velocity: '27.600 km/h',
        orbit_time: '92 minutos',
        last_update: new Date().toLocaleString('pt-BR')
    };
    
    // Atualiza as informações da ISS
    updateISSInfo(lat, lng, issInfo);
}

/**
 * Atualiza as informações da ISS na página
 * 
 * @param {number} lat Latitude
 * @param {number} lng Longitude
 * @param {object} info Informações adicionais da ISS
 */
function updateISSInfo(lat, lng, info = {}) {
    const issInfoContainer = document.getElementById('iss-info');
    
    if (issInfoContainer) {
        // Formata as coordenadas
        const formattedLat = lat.toFixed(4) + '° ' + (lat >= 0 ? 'N' : 'S');
        const formattedLng = lng.toFixed(4) + '° ' + (lng >= 0 ? 'L' : 'O');
        
        // Obtém informações adicionais ou usa valores padrão
        const altitude = info?.altitude || '408 km';
        const velocity = info?.velocity || '27.600 km/h';
        const orbitTime = info?.orbit_time || '92 minutos';
        const lastUpdate = info?.last_update || new Date().toLocaleString('pt-BR');
        
        issInfoContainer.innerHTML = `
            <div class="iss-info-card">
                <h3>Localização Atual da ISS</h3>
                <div class="iss-data-grid">
                    <div class="iss-data-item">
                        <span class="iss-data-label">Latitude:</span>
                        <span class="iss-data-value">${formattedLat}</span>
                    </div>
                    <div class="iss-data-item">
                        <span class="iss-data-label">Longitude:</span>
                        <span class="iss-data-value">${formattedLng}</span>
                    </div>
                    <div class="iss-data-item">
                        <span class="iss-data-label">Altitude:</span>
                        <span class="iss-data-value">${altitude}</span>
                    </div>
                    <div class="iss-data-item">
                        <span class="iss-data-label">Velocidade:</span>
                        <span class="iss-data-value">${velocity}</span>
                    </div>
                    <div class="iss-data-item">
                        <span class="iss-data-label">Tempo de órbita:</span>
                        <span class="iss-data-value">${orbitTime}</span>
                    </div>
                </div>
                <p class="iss-timestamp">Atualizado em: ${lastUpdate}</p>
            </div>
        `;
    }
}

/**
 * Inicializa os dados dos satélites
 */
function initSatelliteData() {
    // Carrega os dados da Terra
    const earthDataElement = document.getElementById('earth-data');
    if (earthDataElement) {
        loadEarthData();
    }
    
    // Carrega os dados do espaço
    const spaceDataElement = document.getElementById('space-data');
    if (spaceDataElement) {
        loadSpaceData();
    }
}

/**
 * Carrega os dados da Terra
 */
function loadEarthData() {
    // Mostra indicadores de carregamento
    const earthImagesContainer = document.getElementById('earth-images');
    const eventsContainer = document.getElementById('earth-events');
    
    if (earthImagesContainer) {
        earthImagesContainer.innerHTML = '<div class="loading-container"><div class="loader"><div class="loader-ring"></div><div class="loader-planet"></div></div><p>Carregando imagens da Terra...</p></div>';
    }
    
    if (eventsContainer) {
        eventsContainer.innerHTML = '<div class="loading-container"><div class="loader"><div class="loader-ring"></div><div class="loader-planet"></div></div><p>Carregando eventos naturais...</p></div>';
    }
    
    // Usa a nova API de imagens diretas que sempre funciona
    fetch('api/imagens_diretas.php?type=earth')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta da API: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Renderiza as imagens da Terra
            if (earthImagesContainer && data.earth_images && data.earth_images.length > 0) {
                renderEarthImages(earthImagesContainer, data.earth_images);
                console.log('Imagens da Terra carregadas com sucesso!');
            } else {
                throw new Error('Nenhuma imagem da Terra encontrada');
            }
            
            // Cria eventos naturais simulados
            if (eventsContainer) {
                const staticEvents = [
                    {
                        id: 'event-1',
                        title: 'Incêndio Florestal na Amazônia',
                        description: 'Grande incêndio florestal detectado na região amazônica',
                        date: new Date().toLocaleDateString('pt-BR'),
                        type: 'wildfire',
                        location: 'Lat: -3.4653, Lon: -62.2159'
                    },
                    {
                        id: 'event-2',
                        title: 'Erupção Vulcânica no Havaí',
                        description: 'Erupção do vulcão Kilauea com fluxo de lava',
                        date: new Date().toLocaleDateString('pt-BR'),
                        type: 'volcano',
                        location: 'Lat: 19.4069, Lon: -155.2834'
                    },
                    {
                        id: 'event-3',
                        title: 'Tempestade Tropical no Atlântico',
                        description: 'Formação de tempestade tropical com ventos de até 100 km/h',
                        date: new Date().toLocaleDateString('pt-BR'),
                        type: 'storm',
                        location: 'Lat: 25.7617, Lon: -80.1918'
                    }
                ];
                renderEarthEvents(eventsContainer, staticEvents);
                console.log('Eventos naturais carregados com sucesso!');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar dados da Terra:', error);
            
            // Em caso de erro, exibe imagens e eventos estáticos
            if (earthImagesContainer) {
                const staticImages = [
                    {
                        id: 'static-1',
                        title: 'Vista da Terra - América do Sul',
                        description: 'Imagem da Terra mostrando a América do Sul e parte do Oceano Atlântico',
                        date: new Date().toLocaleDateString('pt-BR'),
                        url: 'img/earth/earth-south-america.jpg'
                    },
                    {
                        id: 'static-2',
                        title: 'Vista da Terra - América do Norte',
                        description: 'Imagem da Terra mostrando a América do Norte e parte do Oceano Pacífico',
                        date: new Date().toLocaleDateString('pt-BR'),
                        url: 'img/earth/earth-north-america.jpg'
                    }
                ];
                renderEarthImages(earthImagesContainer, staticImages);
            }
            
            if (eventsContainer) {
                const staticEvents = [
                    {
                        id: 'static-event-fallback',
                        title: 'Dados Temporariamente Indisponíveis',
                        description: 'Os dados de eventos naturais estão temporariamente indisponíveis. Tente novamente mais tarde.',
                        date: new Date().toLocaleDateString('pt-BR'),
                        type: 'default',
                        location: 'Global'
                    }
                ];
                renderEarthEvents(eventsContainer, staticEvents);
            }
        });
}

/**
 * Renderiza as imagens da Terra
 * 
 * @param {HTMLElement} container Elemento container
 * @param {Array} images Array de imagens
 */
function renderEarthImages(container, images) {
    let html = '';
    
    images.forEach(image => {
        // Verifica se a URL da imagem é uma URL completa ou um caminho relativo
        const imageUrl = image.url.startsWith('http') ? 
            // Se for URL completa, usa o proxy para evitar problemas de CORS
            `api/proxy.php?url=${encodeURIComponent(image.url)}` : 
            // Se for caminho relativo, usa diretamente
            image.url;
        
        html += `
            <div class="gallery-item">
                <img src="${imageUrl}" alt="${image.title}" onerror="this.src='img/earth/earth-default.jpg'">
                <div class="gallery-item-info">
                    <h3>${image.title}</h3>
                    <p>${image.description}</p>
                    <div class="gallery-item-meta">
                        <span class="gallery-item-date">${image.date}</span>
                        ${image.centroid_coordinates ? 
                            `<span class="gallery-item-coords">Lat: ${image.centroid_coordinates.lat.toFixed(2)}, Lon: ${image.centroid_coordinates.lon.toFixed(2)}</span>` : 
                            ''
                        }
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    initImageGallery();
}

/**
 * Renderiza os eventos naturais na Terra
 * 
 * @param {HTMLElement} container Elemento container
 * @param {Array} events Array de eventos
 */
function renderEarthEvents(container, events) {
    let html = '';
    
    events.forEach(event => {
        html += `
            <div class="event-item">
                <div class="event-icon"><i class="${getEventIcon(event.type)}"></i></div>
                <div class="event-info">
                    <h4>${event.title}</h4>
                    <p>${event.description || ''}</p>
                    <div class="event-meta">
                        <span class="event-date">${event.date || ''}</span>
                        <span class="event-location">${event.location || ''}</span>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

/**
 * Retorna o ícone para o tipo de evento
 * 
 * @param {string} type Tipo de evento
 * @return {string} Classe do ícone
 */
function getEventIcon(type) {
    const icons = {
        'wildfire': 'fas fa-fire',
        'storm': 'fas fa-bolt',
        'volcano': 'fas fa-mountain',
        'earthquake': 'fas fa-globe-americas',
        'flood': 'fas fa-water',
        'drought': 'fas fa-sun',
        'default': 'fas fa-exclamation-circle'
    };
    
    return icons[type] || icons.default;
}

/**
 * Carrega os dados do espaço
 */
function loadSpaceData() {
    // Mostra indicador de carregamento
    const apodContainer = document.getElementById('apod-container');
    if (apodContainer) {
        apodContainer.innerHTML = '<div class="loading-container"><div class="loader"><div class="loader-ring"></div><div class="loader-planet"></div></div><p>Carregando imagem astronômica do dia...</p></div>';
    }
    
    // Usa a nova API de imagens diretas que sempre funciona
    fetch('api/imagens_diretas.php?type=space')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta da API: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Renderiza a imagem astronômica do dia
            if (apodContainer && data.space_images && data.space_images.length > 0) {
                // Usa a primeira imagem como APOD
                const apodData = data.space_images[0];
                renderAPOD(apodContainer, apodData);
                console.log('Imagem astronômica do dia carregada com sucesso!');
                
                // Exibe galeria de imagens espaciais se houver um container para isso
                const spaceGalleryContainer = document.getElementById('space-gallery');
                if (spaceGalleryContainer) {
                    let html = '<div class="gallery-grid">';
                    
                    // Começa do índice 1 pois o primeiro já foi usado como APOD
                    for (let i = 1; i < data.space_images.length; i++) {
                        const image = data.space_images[i];
                        html += `
                            <div class="gallery-item">
                                <img src="${image.url}" alt="${image.title}" onerror="this.src='img/space/space-default.jpg'">
                                <div class="gallery-item-info">
                                    <h3>${image.title}</h3>
                                    <p>${image.description}</p>
                                </div>
                            </div>
                        `;
                    }
                    
                    html += '</div>';
                    spaceGalleryContainer.innerHTML = html;
                    console.log('Galeria de imagens espaciais carregada com sucesso!');
                }
            } else {
                throw new Error('Nenhuma imagem do espaço encontrada');
            }
            
            // Informações dos telescópios e satélites
            const satellitesContainer = document.getElementById('satellites-info');
            if (satellitesContainer) {
                const satellites = [
                    {
                        name: 'Telescópio Espacial Hubble',
                        image: 'img/space/hubble.jpg',
                        description: 'Lançado em 1990, o Hubble é um dos maiores e mais versáteis telescópios espaciais, fornecendo imagens de alta resolução do universo.',
                        launch_date: '24/04/1990',
                        altitude: '540 km',
                        agency: 'NASA/ESA'
                    },
                    {
                        name: 'Telescópio Espacial James Webb',
                        image: 'img/space/james-webb.jpg',
                        description: 'O sucessor do Hubble, projetado para observar objetos muito distantes e fracos no universo, utilizando principalmente o infravermelho.',
                        launch_date: '25/12/2021',
                        altitude: '1,5 milhão km',
                        agency: 'NASA/ESA/CSA'
                    },
                    {
                        name: 'Estação Espacial Internacional',
                        image: 'img/iss/iss.jpg',
                        description: 'Laboratório espacial em órbita terrestre baixa, habitado continuamente desde 2000, servindo como plataforma de pesquisa e colaboração internacional.',
                        launch_date: '20/11/1998',
                        altitude: '408 km',
                        agency: 'NASA/Roscosmos/ESA/JAXA/CSA'
                    }
                ];
                
                let html = '';
                satellites.forEach(satellite => {
                    html += `
                        <div class="satellite-card">
                            <h3>${satellite.name}</h3>
                            <div class="satellite-image">
                                <img src="${satellite.image}" alt="${satellite.name}" onerror="this.src='img/space/space-default.jpg'">
                            </div>
                            <div class="satellite-info">
                                <p>${satellite.description}</p>
                                <ul>
                                    <li><strong>Lançamento:</strong> ${satellite.launch_date}</li>
                                    <li><strong>Altitude:</strong> ${satellite.altitude}</li>
                                    <li><strong>Agência:</strong> ${satellite.agency}</li>
                                </ul>
                            </div>
                        </div>
                    `;
                });
                satellitesContainer.innerHTML = html;
                console.log('Informações dos satélites carregadas com sucesso!');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar dados do espaço:', error);
            
            // Em caso de erro, exibe imagem estática
            if (apodContainer) {
                const fallbackApod = {
                    title: 'Nebulosa de Órion',
                    explanation: 'A Nebulosa de Órion (também conhecida como Messier 42, M42 ou NGC 1976) é uma nebulosa difusa situada na constelação de Órion. É uma das nebulosas mais brilhantes e pode ser vista a olho nu no céu noturno.',
                    date: new Date().toLocaleDateString('pt-BR'),
                    url: 'img/space/space-default.jpg',
                    media_type: 'image',
                    copyright: 'NASA/ESA'
                };
                renderAPOD(apodContainer, fallbackApod);
            }
        });
}

/**
 * Renderiza a imagem astronômica do dia
 * 
 * @param {HTMLElement} container Elemento container
 * @param {Object} apod Dados da imagem
 */
function renderAPOD(container, apod) {
    // Verifica se a URL da imagem é uma URL completa ou um caminho relativo
    const imageUrl = apod.url && apod.url.startsWith('http') ? 
        // Se for URL completa, usa o proxy para evitar problemas de CORS
        `api/proxy.php?url=${encodeURIComponent(apod.url)}` : 
        // Se for caminho relativo, usa diretamente
        apod.url || 'img/space/space-default.jpg';
    
    // Verifica o tipo de mídia
    const isVideo = apod.media_type === 'video';
    
    let mediaHtml = '';
    if (isVideo) {
        // Se for vídeo, exibe um iframe ou um link
        if (apod.url && (apod.url.includes('youtube.com') || apod.url.includes('vimeo.com'))) {
            mediaHtml = `
                <div class="apod-video">
                    <iframe src="${apod.url}" frameborder="0" allowfullscreen></iframe>
                </div>
            `;
        } else {
            mediaHtml = `
                <div class="apod-video-link">
                    <a href="${apod.url}" target="_blank" class="btn-view">Assistir Vídeo <i class="fas fa-external-link-alt"></i></a>
                </div>
            `;
        }
    } else {
        // Se for imagem, exibe normalmente
        mediaHtml = `
            <div class="apod-image">
                <img src="${imageUrl}" alt="${apod.title}" onerror="this.src='img/space/space-default.jpg'">
            </div>
        `;
    }
    
    let html = `
        <div class="apod-card">
            <h3 class="apod-title">${apod.title}</h3>
            <div class="apod-date">${apod.date}</div>
            ${mediaHtml}
            <div class="apod-description">
                <p>${apod.explanation || 'Sem descrição disponível.'}</p>
            </div>
            <div class="apod-copyright">
                ${apod.copyright ? `© ${apod.copyright}` : 'Imagem: NASA'}
            </div>
        </div>
    `;
    
    container.innerHTML = html;
    
    // Adiciona evento de clique na imagem (apenas se for imagem)
    if (!isVideo) {
        const apodImage = container.querySelector('.apod-image img');
        if (apodImage) {
            apodImage.addEventListener('click', function() {
                createLightbox(imageUrl, apod.title);
            });
        }
    }
}

/**
 * Inicializa as animações ao scroll
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    const checkIfInView = () => {
        animatedElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementBottom = element.getBoundingClientRect().bottom;
            
            // Verifica se o elemento está visível na viewport
            const isInView = (elementTop <= window.innerHeight * 0.8) && (elementBottom >= 0);
            
            if (isInView) {
                element.classList.add('animate-fade-in');
            }
        });
    };
    
    // Verifica na carga da página
    checkIfInView();
    
    // Verifica ao scroll
    window.addEventListener('scroll', checkIfInView);
}
