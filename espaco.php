<?php
/**
 * SatéliteVision - Página do Espaço
 * Visualização de imagens do espaço capturadas por telescópios e satélites
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Espaço', 'Visualização de imagens do espaço capturadas por telescópios e satélites');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Espaço Profundo</h1>
    <p class="hero-subtitle">Explore as maravilhas do universo através de imagens espetaculares capturadas pelos mais avançados telescópios espaciais. Descubra galáxias, nebulosas, planetas e outros objetos celestes.</p>
</section>

<!-- Seção de Imagem Astronômica do Dia -->
<section id="apod" class="featured-section">
    <h2 class="section-title">Imagem Astronômica do Dia</h2>
    <p class="section-description">A NASA seleciona diariamente uma imagem ou fotografia do nosso universo fascinante, junto com uma breve explicação escrita por um astrônomo profissional.</p>
    
    <div id="apod-container" class="apod-container">
        <?php render_loader('Carregando imagem astronômica do dia...'); ?>
    </div>
</section>

<!-- Seção de Imagens do Universo -->
<section id="universe-images" class="featured-section">
    <h2 class="section-title">🌌 Imagens do Universo (Hubble & James Webb)</h2>
    <p class="section-description">Explore as maravilhas do cosmos através de imagens espetaculares capturadas pelos mais avançados telescópios espaciais.</p>
    
    <div class="space-gallery" style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px;">
        <!-- Imagem 1: Nebulosa de Carina (James Webb) -->
        <div class="space-image-card" style="width: 30%;">
            <img src="https://webbtelescope.org/files/live/sites/webb/files/images/media-resources/jwst-image-gallery/webb-full-images/carina_nebula.jpg" 
                 alt="Nebulosa de Carina - James Webb" 
                 style="width: 100%; border-radius: 10px;">
            <p class="image-caption">Nebulosa de Carina - James Webb</p>
        </div>
        
        <!-- Imagem 2: Pilares da Criação (Hubble) -->
        <div class="space-image-card" style="width: 30%;">
            <img src="https://cdn.spacetelescope.org/archives/images/screen/heic1501a.jpg" 
                 alt="Pilares da Criação - Hubble" 
                 style="width: 100%; border-radius: 10px;">
            <p class="image-caption">Pilares da Criação - Hubble</p>
        </div>
        
        <!-- Imagem 3: Galáxia do Redemoinho (Hubble) -->
        <div class="space-image-card" style="width: 30%;">
            <img src="https://www.nasa.gov/sites/default/files/thumbnails/image/potw2133a.jpg" 
                 alt="Galáxia do Redemoinho - Hubble" 
                 style="width: 100%; border-radius: 10px;">
            <p class="image-caption">Galáxia do Redemoinho - Hubble</p>
        </div>
    </div>
</section>

<!-- Seção Hubble -->
<section id="hubble" class="featured-section">
    <h2 class="section-title">Telescópio Espacial Hubble</h2>
    <p class="section-description">Lançado em 1990, o Hubble revolucionou a astronomia com suas imagens impressionantes de objetos distantes no universo, livres da distorção causada pela atmosfera terrestre.</p>
    
    <div class="telescope-info">
        <div class="telescope-image">
            <img src="img/hubble-telescope.jpg" alt="Telescópio Espacial Hubble">
        </div>
        <div class="telescope-data">
            <div class="telescope-data-item">
                <span class="telescope-data-label">Lançamento:</span>
                <span class="telescope-data-value">24 de abril de 1990</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Altitude:</span>
                <span class="telescope-data-value">540 km</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Diâmetro do Espelho:</span>
                <span class="telescope-data-value">2,4 metros</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Órbita:</span>
                <span class="telescope-data-value">97 minutos</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Agência:</span>
                <span class="telescope-data-value">NASA/ESA</span>
            </div>
        </div>
    </div>
    
    <h3 class="subsection-title">Galeria de Imagens do Hubble</h3>
    
    <div class="image-gallery">
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/hubble-image1.jpg" alt="Nebulosa da Águia">
            </div>
            <div class="gallery-caption">
                <h4>Nebulosa da Águia</h4>
                <p>Também conhecida como M16, é uma região de formação estelar ativa.</p>
                <span class="gallery-date">Crédito: NASA/ESA</span>
            </div>
        </div>
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/hubble-image2.jpg" alt="Galáxia do Sombreiro">
            </div>
            <div class="gallery-caption">
                <h4>Galáxia do Sombreiro</h4>
                <p>Uma galáxia espiral localizada na constelação de Virgem.</p>
                <span class="gallery-date">Crédito: NASA/ESA</span>
            </div>
        </div>
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/hubble-image3.jpg" alt="Nebulosa da Borboleta">
            </div>
            <div class="gallery-caption">
                <h4>Nebulosa da Borboleta</h4>
                <p>Uma nebulosa bipolar na constelação de Ophiuchus.</p>
                <span class="gallery-date">Crédito: NASA/ESA</span>
            </div>
        </div>
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/hubble-image4.jpg" alt="Pilares da Criação">
            </div>
            <div class="gallery-caption">
                <h4>Pilares da Criação</h4>
                <p>Colunas de gás e poeira na Nebulosa da Águia onde novas estrelas estão se formando.</p>
                <span class="gallery-date">Crédito: NASA/ESA</span>
            </div>
        </div>
    </div>
</section>

<!-- Seção James Webb -->
<section id="webb" class="featured-section">
    <h2 class="section-title">Telescópio Espacial James Webb</h2>
    <p class="section-description">O sucessor do Hubble, o James Webb é o mais poderoso telescópio espacial já construído, projetado para observar objetos extremamente distantes no universo usando principalmente o infravermelho.</p>
    
    <div class="telescope-info">
        <div class="telescope-image">
            <img src="img/webb-telescope.jpg" alt="Telescópio Espacial James Webb">
        </div>
        <div class="telescope-data">
            <div class="telescope-data-item">
                <span class="telescope-data-label">Lançamento:</span>
                <span class="telescope-data-value">25 de dezembro de 2021</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Localização:</span>
                <span class="telescope-data-value">Ponto L2 (1,5 milhão km da Terra)</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Diâmetro do Espelho:</span>
                <span class="telescope-data-value">6,5 metros</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Temperatura de Operação:</span>
                <span class="telescope-data-value">-233°C</span>
            </div>
            <div class="telescope-data-item">
                <span class="telescope-data-label">Agência:</span>
                <span class="telescope-data-value">NASA/ESA/CSA</span>
            </div>
        </div>
    </div>
    
    <h3 class="subsection-title">Primeiras Imagens do James Webb</h3>
    
    <div class="image-gallery">
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/webb-image1.jpg" alt="Campo Profundo">
            </div>
            <div class="gallery-caption">
                <h4>Campo Profundo do JWST</h4>
                <p>A imagem mais profunda e nítida do universo distante até hoje.</p>
                <span class="gallery-date">Crédito: NASA/ESA/CSA</span>
            </div>
        </div>
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/webb-image2.jpg" alt="Nebulosa do Anel Sul">
            </div>
            <div class="gallery-caption">
                <h4>Nebulosa do Anel Sul</h4>
                <p>Uma nebulosa planetária que mostra o ciclo de vida estelar.</p>
                <span class="gallery-date">Crédito: NASA/ESA/CSA</span>
            </div>
        </div>
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/webb-image3.jpg" alt="Quinteto de Stephan">
            </div>
            <div class="gallery-caption">
                <h4>Quinteto de Stephan</h4>
                <p>Um grupo compacto de galáxias localizado na constelação de Pégaso.</p>
                <span class="gallery-date">Crédito: NASA/ESA/CSA</span>
            </div>
        </div>
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/webb-image4.jpg" alt="Nebulosa de Carina">
            </div>
            <div class="gallery-caption">
                <h4>Nebulosa de Carina</h4>
                <p>Uma região de formação estelar revelando detalhes nunca antes vistos.</p>
                <span class="gallery-date">Crédito: NASA/ESA/CSA</span>
            </div>
        </div>
    </div>
</section>

<!-- Seção de Exploração Interativa -->
<section class="featured-section">
    <h2 class="section-title">Exploração Interativa</h2>
    <p class="section-description">Explore o universo de forma interativa, navegando por diferentes objetos celestes e aprendendo sobre eles.</p>
    
    <div class="space-explorer">
        <div class="explorer-controls">
            <div class="explorer-control-group">
                <label for="explorer-object">Objeto Celeste:</label>
                <select id="explorer-object">
                    <option value="solar-system">Sistema Solar</option>
                    <option value="milky-way">Via Láctea</option>
                    <option value="andromeda">Galáxia de Andrômeda</option>
                    <option value="black-hole">Buraco Negro</option>
                    <option value="nebula">Nebulosas</option>
                </select>
            </div>
            <button id="explorer-view" class="btn-view">Explorar <i class="fas fa-rocket"></i></button>
        </div>
        
        <div id="space-viewer" class="space-viewer">
            <div class="space-viewer-placeholder">
                <i class="fas fa-galaxy"></i>
                <p>Selecione um objeto celeste para explorar</p>
            </div>
        </div>
    </div>
</section>

<!-- Scripts específicos da página -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Explorador espacial
    const explorerViewBtn = document.getElementById('explorer-view');
    
    if (explorerViewBtn) {
        explorerViewBtn.addEventListener('click', function() {
            const object = document.getElementById('explorer-object').value;
            const viewer = document.getElementById('space-viewer');
            
            viewer.innerHTML = `
                <div class="loading-effect">
                    <div class="loader">
                        <div class="loader-ring"></div>
                        <div class="loader-planet"></div>
                    </div>
                    <p>Carregando visualização de ${document.getElementById('explorer-object').options[document.getElementById('explorer-object').selectedIndex].text}...</p>
                </div>
            `;
            
            // Simula o tempo de carregamento
            setTimeout(function() {
                viewer.innerHTML = `
                    <div class="space-object-viewer">
                        <img src="img/${object}.jpg" alt="${document.getElementById('explorer-object').options[document.getElementById('explorer-object').selectedIndex].text}">
                        <div class="object-info">
                            <h3>${document.getElementById('explorer-object').options[document.getElementById('explorer-object').selectedIndex].text}</h3>
                            <p>${getObjectDescription(object)}</p>
                        </div>
                    </div>
                `;
                
                // Adiciona evento de clique na imagem
                const objectImage = viewer.querySelector('.space-object-viewer img');
                if (objectImage) {
                    objectImage.addEventListener('click', function() {
                        createLightbox(this.src, this.alt);
                    });
                }
            }, 1500);
        });
    }
    
    // Descrições dos objetos celestes
    function getObjectDescription(object) {
        const descriptions = {
            'solar-system': 'Nosso sistema solar consiste no Sol e todos os objetos que orbitam ao seu redor, incluindo planetas, luas, asteroides, cometas e poeira espacial.',
            'milky-way': 'A Via Láctea é uma galáxia espiral barrada que contém nosso Sistema Solar. Tem entre 100 e 400 bilhões de estrelas e pelo menos esse número de planetas.',
            'andromeda': 'A Galáxia de Andrômeda (M31) é a galáxia espiral mais próxima da Via Láctea, a aproximadamente 2,5 milhões de anos-luz de distância.',
            'black-hole': 'Um buraco negro é uma região do espaço-tempo onde a gravidade é tão forte que nada, nem mesmo a luz, consegue escapar de seu interior.',
            'nebula': 'Nebulosas são nuvens de gás e poeira no espaço. Algumas são regiões onde novas estrelas estão se formando, enquanto outras são restos de estrelas mortas.'
        };
        
        return descriptions[object] || 'Informações não disponíveis para este objeto.';
    }
});
</script>

<?php
// Renderiza o rodapé
render_footer();
?>
