<?php
/**
 * SatéliteVision - Página da ISS (Parte 3)
 * Visualizações ao vivo e fotos da Estação Espacial Internacional
 */

// Esta parte deve ser incluída após iss_parte2.php
?>

<!-- Seção de Visualização ao Vivo -->
<section class="featured-section">
    <h2 class="section-title">Visualização ao Vivo</h2>
    <p class="section-description">Assista à transmissão ao vivo da Terra vista da Estação Espacial Internacional.</p>
    
    <div class="live-feed-container">
        <div class="live-feed">
            <div class="live-indicator">
                <span class="live-dot"></span>
                <span class="live-text">AO VIVO</span>
            </div>
            <iframe id="iss-live-feed" src="https://www.youtube.com/embed/86YLFOog4GM?autoplay=1&mute=1" frameborder="0" allowfullscreen></iframe>
        </div>
        
        <div class="live-feed-info">
            <h3>Transmissão da NASA</h3>
            <p>Esta transmissão ao vivo da ISS inclui vistas internas quando a tripulação está no trabalho e vistas da Terra quando a tripulação não está disponível. A transmissão é acompanhada de áudio das conversas entre a tripulação e o Centro de Controle da Missão.</p>
            <p>Quando a transmissão ao vivo não está disponível - quando a estação está fora do alcance dos satélites de comunicação TDRS - você verá uma tela azul.</p>
            <div class="feed-controls">
                <button id="feed-hd" class="btn-secondary">HD <i class="fas fa-hd"></i></button>
                <button id="feed-mute" class="btn-secondary">Mudo <i class="fas fa-volume-mute"></i></button>
                <button id="feed-fullscreen" class="btn-secondary">Tela Cheia <i class="fas fa-expand"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- Seção de Galeria de Fotos -->
<section class="featured-section">
    <h2 class="section-title">Galeria de Fotos</h2>
    <p class="section-description">Imagens espetaculares capturadas pelos astronautas a bordo da ISS.</p>
    
    <div class="image-gallery">
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/iss-photo1.jpg" alt="Aurora vista da ISS">
            </div>
            <div class="gallery-caption">
                <h4>Aurora Boreal</h4>
                <p>Aurora boreal vista da ISS sobrevoando o norte do Canadá.</p>
                <span class="gallery-date">Crédito: NASA</span>
            </div>
        </div>
        
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/iss-photo2.jpg" alt="Astronauta em caminhada espacial">
            </div>
            <div class="gallery-caption">
                <h4>Caminhada Espacial</h4>
                <p>Astronauta realizando manutenção externa durante uma caminhada espacial.</p>
                <span class="gallery-date">Crédito: NASA</span>
            </div>
        </div>
        
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/iss-photo3.jpg" alt="Nascer do Sol visto da ISS">
            </div>
            <div class="gallery-caption">
                <h4>Nascer do Sol Orbital</h4>
                <p>A ISS testemunha 16 nasceres e pores do sol a cada dia terrestre.</p>
                <span class="gallery-date">Crédito: NASA</span>
            </div>
        </div>
        
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/iss-photo4.jpg" alt="Interior da ISS">
            </div>
            <div class="gallery-caption">
                <h4>Interior da Estação</h4>
                <p>Astronautas trabalhando no interior do laboratório Destiny.</p>
                <span class="gallery-date">Crédito: NASA</span>
            </div>
        </div>
        
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/iss-photo5.jpg" alt="ISS sobrevoando a Terra">
            </div>
            <div class="gallery-caption">
                <h4>ISS em Órbita</h4>
                <p>A estação espacial fotografada por uma nave de abastecimento.</p>
                <span class="gallery-date">Crédito: NASA</span>
            </div>
        </div>
        
        <div class="gallery-item">
            <div class="gallery-image">
                <img src="img/iss-photo6.jpg" alt="Experimento científico na ISS">
            </div>
            <div class="gallery-caption">
                <h4>Ciência em Microgravidade</h4>
                <p>Experimento científico sendo conduzido no ambiente de microgravidade.</p>
                <span class="gallery-date">Crédito: NASA</span>
            </div>
        </div>
    </div>
</section>
