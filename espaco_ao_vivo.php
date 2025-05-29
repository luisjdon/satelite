<?php
/**
 * SatéliteVision - Visualizador de Imagens do Espaço
 * Página para visualizar imagens do espaço em tempo real
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o título da página
$page_title = 'Visualizador de Imagens do Espaço';
$page_description = 'Visualize imagens do espaço em tempo real através de telescópios e satélites';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - SatéliteVision</title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos personalizados para o visualizador de imagens do espaço */
        .space-section {
            margin-bottom: 50px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 100, 255, 0.3);
        }
        
        .section-title {
            color: #00a8ff;
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #00a8ff;
        }
        
        .section-description {
            color: #ccc;
            margin-bottom: 25px;
            font-size: 1.1em;
            line-height: 1.6;
        }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .gallery-item {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 150, 255, 0.4);
        }
        
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        
        .image-caption {
            padding: 15px;
        }
        
        .image-caption h3 {
            color: #00a8ff;
            margin-bottom: 8px;
            font-family: 'Orbitron', sans-serif;
        }
        
        .image-caption p {
            color: #ccc;
            font-size: 0.9em;
            line-height: 1.5;
        }
        
        .apod-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
        }
        
        .apod-image img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
        
        .apod-info {
            padding: 10px;
        }
        
        .apod-info h3 {
            color: #00a8ff;
            margin-bottom: 10px;
            font-family: 'Orbitron', sans-serif;
        }
        
        .apod-date {
            color: #aaa;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        
        .apod-description {
            color: #ddd;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .apod-credit {
            color: #888;
            font-size: 0.9em;
            font-style: italic;
        }
        
        /* Modal para visualização em tela cheia */
        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            overflow: auto;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .modal-content {
            max-width: 90%;
            max-height: 90vh;
            margin: auto;
            display: block;
            box-shadow: 0 0 30px rgba(0, 150, 255, 0.5);
        }
        
        .modal-caption {
            color: white;
            text-align: center;
            padding: 20px;
            max-width: 80%;
            margin: 20px auto;
        }
        
        .close-modal {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .apod-container {
                grid-template-columns: 1fr;
            }
            
            .image-gallery {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="stars-background"></div>
    <div class="overlay-grid"></div>
    <div class="container">
        <header class="main-header">
            <div class="logo">
                <a href="index.php">
                    <span class="logo-icon"><i class="fas fa-satellite"></i></span>
                    <span class="logo-text">SatéliteVision</span>
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="nav-link"><i class="fas fa-home"></i> Início</a></li>
                    <li><a href="terra.php" class="nav-link"><i class="fas fa-globe-americas"></i> Terra</a></li>
                    <li><a href="espaco.php" class="nav-link"><i class="fas fa-star"></i> Espaço</a></li>
                    <li><a href="iss.php" class="nav-link"><i class="fas fa-space-shuttle"></i> ISS</a></li>
                    <li><a href="sobre.php" class="nav-link"><i class="fas fa-info-circle"></i> Sobre</a></li>
                </ul>
            </nav>
        </header>
        <main class="content">
            <h1 class="page-title">Visualizador de Imagens do Espaço</h1>
            <p class="page-description">Explore o universo através de imagens e vídeos capturados por telescópios e satélites em tempo real.</p>
            
            <!-- Seção de vídeos ao vivo da ISS -->
            <section class="space-section">
                <h2 class="section-title"><i class="fas fa-satellite"></i> 🌍 Live HD da Estação Espacial Internacional</h2>
                <p class="section-description">Assista à transmissão ao vivo da Terra diretamente da Estação Espacial Internacional.</p>
                
                <div class="video-container">
                    <!-- Live High-Definition View -->
                    <iframe width="100%" height="500"
                            src="https://www.youtube.com/embed/H999s0P1Er0?autoplay=1&mute=1"
                            title="Live HD View from the ISS"
                            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    </iframe>
                </div>
                
                <div class="video-container" style="margin-top: 20px;">
                    <!-- Live Video Stream -->
                    <iframe width="100%" height="500"
                            src="https://www.youtube.com/embed/DIgkvm2nmHc?autoplay=1&mute=1"
                            title="Live Video from the ISS"
                            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    </iframe>
                </div>
                
                <p class="video-note">Esses vídeos são transmitidos diretamente da Estação Espacial Internacional. Durante períodos de perda de sinal ou noite orbital, a imagem pode aparecer preta ou exibir dados técnicos.</p>
            </section>
            
            <!-- Seção do Telescópio Hubble -->
            <section class="space-section">
                <h2 class="section-title"><i class="fas fa-telescope"></i> Telescópio Hubble</h2>
                <p class="section-description">O Telescópio Espacial Hubble é um dos observatórios mais importantes da história da astronomia. Lançado em 1990, ele continua capturando imagens incríveis do universo.</p>
                
                <div class="image-gallery" id="hubble-gallery">
                    <div class="gallery-item">
                        <img src="https://esahubble.org/media/archives/images/large/heic2017a.jpg" alt="Nebulosa do Véu" onerror="this.src='img/space/space-default.jpg'">
                        <div class="image-caption">
                            <h3>Nebulosa do Véu</h3>
                            <p>Remanescente de supernova na constelação de Cisne, a aproximadamente 2,100 anos-luz da Terra.</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://esahubble.org/media/archives/images/large/heic0715a.jpg" alt="Galáxia do Redemoinho" onerror="this.src='img/space/space-default.jpg'">
                        <div class="image-caption">
                            <h3>Galáxia do Redemoinho (M51)</h3>
                            <p>Uma galáxia espiral clássica localizada a aproximadamente 23 milhões de anos-luz da Terra.</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://esahubble.org/media/archives/images/large/heic0506a.jpg" alt="Pilares da Criação" onerror="this.src='img/space/space-default.jpg'">
                        <div class="image-caption">
                            <h3>Pilares da Criação</h3>
                            <p>Colunas de gás e poeira na Nebulosa da Águia, onde novas estrelas estão se formando.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Seção do Telescópio James Webb -->
            <section class="space-section">
                <h2 class="section-title"><i class="fas fa-satellite"></i> Telescópio James Webb</h2>
                <p class="section-description">O Telescópio Espacial James Webb é o maior e mais poderoso telescópio espacial já lançado. Ele permite observações do universo distante com detalhes sem precedentes.</p>
                
                <div class="image-gallery" id="webb-gallery">
                    <div class="gallery-item">
                        <img src="https://stsci-opo.org/STScI-01GA6KKWG229B16K4Q38CH3BXS.png" alt="Nebulosa de Carina" onerror="this.src='img/space/space-default.jpg'">
                        <div class="image-caption">
                            <h3>Nebulosa de Carina</h3>
                            <p>Uma das maiores e mais brilhantes nebulosas do céu, localizada a cerca de 7.600 anos-luz da Terra.</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://stsci-opo.org/STScI-01G8H1K2BCNATEZSKVRN9Z69XD.png" alt="Quinteto de Stephan" onerror="this.src='img/space/space-default.jpg'">
                        <div class="image-caption">
                            <h3>Quinteto de Stephan</h3>
                            <p>Um grupo compacto de cinco galáxias, quatro das quais formam o primeiro grupo compacto de galáxias já descoberto.</p>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="https://stsci-opo.org/STScI-01G8GZJM11Z0D6YS8Y1YBJBMH3.png" alt="SMACS 0723" onerror="this.src='img/space/space-default.jpg'">
                        <div class="image-caption">
                            <h3>Campo Profundo SMACS 0723</h3>
                            <p>A imagem infravermelha mais profunda e nítida do universo distante até hoje, mostrando galáxias formadas logo após o Big Bang.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Seção da Imagem Astronômica do Dia -->
            <section class="space-section">
                <h2 class="section-title"><i class="fas fa-star"></i> Imagem Astronômica do Dia</h2>
                <p class="section-description">A NASA seleciona diariamente uma imagem ou fotografia do nosso universo fascinante, junto com uma breve explicação escrita por um astrônomo profissional.</p>
                
                <div class="apod-container" id="apod-container">
                    <div class="apod-image">
                        <img src="https://apod.nasa.gov/apod/image/2305/MilkyWayWaterfall_XieJie_960.jpg" alt="Imagem Astronômica do Dia" onerror="this.src='img/space/space-default.jpg'">
                    </div>
                    <div class="apod-info">
                        <h3>Via Láctea sobre Cachoeira</h3>
                        <p class="apod-date">Imagem de exemplo (as imagens são atualizadas diariamente)</p>
                        <p class="apod-description">A Via Láctea se ergue majestosamente sobre uma cachoeira nas montanhas da China. A faixa brilhante que atravessa o céu noturno é o plano da nossa galáxia, composta por bilhões de estrelas, poeira e gás.</p>
                        <p class="apod-credit">Crédito: Xie Jie</p>
                    </div>
                </div>
            </section>
            
        </main>
    </div>
    <!-- Modal para visualização em tela cheia -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
        <div class="modal-caption" id="modalCaption"></div>
    </div>

    <script>
    // Função para inicializar o visualizador de imagens
    document.addEventListener('DOMContentLoaded', function() {
        // Configuração do modal de visualização de imagens
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        const closeModal = document.querySelector('.close-modal');
        
        // Adiciona evento de clique em todas as imagens da galeria
        const galleryImages = document.querySelectorAll('.gallery-item img');
        galleryImages.forEach(img => {
            img.onclick = function() {
                modal.style.display = 'flex';
                modalImg.src = this.src;
                
                // Pega a legenda da imagem
                const caption = this.parentElement.querySelector('.image-caption h3');
                const description = this.parentElement.querySelector('.image-caption p');
                
                if (caption && description) {
                    modalCaption.innerHTML = `<h3>${caption.textContent}</h3><p>${description.textContent}</p>`;
                } else {
                    modalCaption.innerHTML = '';
                }
            }
        });
        
        // Adiciona evento de clique na imagem APOD
        const apodImage = document.querySelector('.apod-image img');
        if (apodImage) {
            apodImage.onclick = function() {
                modal.style.display = 'flex';
                modalImg.src = this.src;
                
                // Pega a legenda da imagem APOD
                const title = document.querySelector('.apod-info h3');
                const description = document.querySelector('.apod-description');
                
                if (title && description) {
                    modalCaption.innerHTML = `<h3>${title.textContent}</h3><p>${description.textContent}</p>`;
                } else {
                    modalCaption.innerHTML = '';
                }
            }
        }
        
        // Fecha o modal quando clicar no X
        closeModal.onclick = function() {
            modal.style.display = 'none';
        }
        
        // Fecha o modal quando clicar fora da imagem
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        console.log('Visualizador de imagens do espaço inicializado com sucesso!');
    });
    </script>
</body>
</html>