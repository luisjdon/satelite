<?php
/**
 * SatéliteVision - Página de Telescópios Públicos
 * Visualização de imagens capturadas por telescópios com acesso aberto
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Telescópios Públicos', 'Acesso a imagens de telescópios públicos ao redor do mundo');
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Telescópios Públicos</h1>
    <p class="hero-subtitle">Explore imagens astronômicas capturadas por telescópios reais acessíveis ao público em diferentes partes do mundo.</p>
</section>

<!-- Seção Las Cumbres Observatory -->
<section id="lco" class="featured-section">
    <h2 class="section-title">🔭 Imagens Recentes do Telescópio LCO</h2>
    <p class="section-description">O Las Cumbres Observatory (LCO) é uma rede global de telescópios robóticos localizados no Havaí, Chile, África do Sul e outros locais. Estas imagens são capturadas por esses telescópios e disponibilizadas publicamente.</p>
    
    <div id="lco-gallery" class="lco-container">
        <div class="loader">Carregando imagens dos telescópios...</div>
    </div>
    
    <script>
    // Carrega as imagens do LCO
    fetch('api/lco.php')
      .then(res => res.text())
      .then(html => {
        document.getElementById('lco-gallery').innerHTML = html;
      })
      .catch(err => {
        console.error("Erro ao buscar imagens do LCO:", err);
        document.getElementById('lco-gallery').innerHTML = "<p class='error-message'>Erro ao carregar imagens. Tente novamente mais tarde.</p>";
      });
    </script>
</section>

<!-- Seção Sobre Telescópios Públicos -->
<section id="about-telescopes" class="featured-section">
    <h2 class="section-title">Sobre Telescópios Públicos</h2>
    <p class="section-description">Telescópios públicos são instrumentos astronômicos que disponibilizam suas imagens e dados para qualquer pessoa interessada em astronomia, desde estudantes até pesquisadores amadores.</p>
    
    <div class="info-cards">
        <div class="info-card">
            <h3><i class="fas fa-globe"></i> Rede Global</h3>
            <p>Telescópios distribuídos ao redor do mundo permitem observações contínuas do céu, independentemente da hora do dia.</p>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-robot"></i> Operação Robótica</h3>
            <p>Muitos telescópios modernos são completamente automatizados, permitindo observações programadas sem intervenção humana direta.</p>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-university"></i> Uso Educacional</h3>
            <p>Escolas e universidades utilizam esses telescópios para projetos educacionais, permitindo que estudantes tenham contato com equipamentos astronômicos profissionais.</p>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-search"></i> Ciência Cidadã</h3>
            <p>Astrônomos amadores podem contribuir para descobertas científicas reais utilizando dados desses telescópios públicos.</p>
        </div>
    </div>
</section>

<!-- Seção Como Acessar -->
<section id="how-to-access" class="featured-section">
    <h2 class="section-title">Como Acessar Telescópios Públicos</h2>
    <p class="section-description">Existem várias maneiras de acessar e utilizar telescópios públicos para suas próprias observações astronômicas.</p>
    
    <div class="steps-container">
        <div class="step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Acesse o site do LCO</h3>
                <p>Visite <a href="https://observe.lco.global/" target="_blank">observe.lco.global</a> e crie uma conta gratuita.</p>
            </div>
        </div>
        
        <div class="step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Escolha um objeto celeste</h3>
                <p>Selecione uma galáxia, estrela, planeta ou outro objeto astronômico que deseja observar.</p>
            </div>
        </div>
        
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Solicite uma observação</h3>
                <p>Preencha um formulário especificando o objeto e os parâmetros de observação desejados.</p>
            </div>
        </div>
        
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Receba suas imagens</h3>
                <p>Após o telescópio realizar a observação, você receberá as imagens capturadas para download.</p>
            </div>
        </div>
    </div>
</section>

<style>
.lco-container {
    margin-top: 20px;
}

.lco-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.lco-image-card {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.lco-image-card:hover {
    transform: translateY(-5px);
}

.lco-info {
    padding: 15px;
}

.lco-info h3 {
    margin-top: 0;
    color: #4da6ff;
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.info-card {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    padding: 20px;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
}

.info-card h3 {
    color: #4da6ff;
}

.steps-container {
    margin-top: 20px;
}

.step {
    display: flex;
    margin-bottom: 20px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    padding: 15px;
}

.step-number {
    background: #4da6ff;
    color: #000;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2em;
    margin-right: 15px;
}

.step-content {
    flex: 1;
}

.step-content h3 {
    margin-top: 0;
    color: #4da6ff;
}

.error-message {
    background: rgba(255, 50, 50, 0.2);
    border-left: 4px solid #ff3232;
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
}

.loader {
    text-align: center;
    padding: 30px;
    font-style: italic;
    color: #aaa;
}
</style>

<?php
// Renderiza o rodapé
render_footer();
?>
