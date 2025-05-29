<?php
/**
 * SatéliteVision - NASA Image and Video Library
 * Acesso à biblioteca de imagens e vídeos da NASA
 */

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Biblioteca NASA', 'Explore a biblioteca oficial de imagens e vídeos da NASA');

// Obtém o termo de busca da URL ou usa o padrão
$search_term = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : 'galaxy';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
?>

<!-- Seção Hero -->
<section class="hero-section">
    <h1 class="hero-title">Biblioteca de Imagens NASA</h1>
    <p class="hero-subtitle">Explore milhões de imagens e vídeos da NASA, desde o início da exploração espacial até os dias atuais.</p>
</section>

<!-- Seção de Busca -->
<section class="search-section">
    <form id="nasa-search-form" class="search-form" method="get" action="nasa.php">
        <div class="search-container">
            <input type="text" name="q" id="search-term" placeholder="Buscar imagens (ex: galaxy, nebula, earth...)" value="<?php echo $search_term; ?>" class="search-input">
            <button type="submit" class="search-button"><i class="fas fa-search"></i> Buscar</button>
        </div>
        
        <div class="quick-filters">
            <span class="filter-label">Filtros rápidos:</span>
            <a href="?q=earth" class="filter-tag <?php echo ($search_term == 'earth') ? 'active' : ''; ?>">🌍 Terra</a>
            <a href="?q=nebula" class="filter-tag <?php echo ($search_term == 'nebula') ? 'active' : ''; ?>">🌌 Nebulosa</a>
            <a href="?q=ISS" class="filter-tag <?php echo ($search_term == 'ISS') ? 'active' : ''; ?>">🚀 ISS</a>
            <a href="?q=Hubble" class="filter-tag <?php echo ($search_term == 'Hubble') ? 'active' : ''; ?>">🔭 Hubble</a>
            <a href="?q=Webb" class="filter-tag <?php echo ($search_term == 'Webb') ? 'active' : ''; ?>">📡 James Webb</a>
            <a href="?q=<?php echo urlencode(get_random_search_term()); ?>" class="filter-tag random-tag">🎲 Aleatório</a>
        </div>
    </form>
</section>

<!-- Seção de Resultados -->
<section class="results-section">
    <h2 class="section-title">🌌 Imagens Espaciais da NASA: <?php echo ucfirst($search_term); ?></h2>
    
    <div id="nasa-images" class="nasa-gallery">
        <?php
        // URL da API da NASA
        $url = "https://images-api.nasa.gov/search?q=" . urlencode($search_term) . "&media_type=image&page=" . $page;
        
        try {
            // Obtém os dados da API
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            
            if (!$data || !isset($data['collection']['items']) || empty($data['collection']['items'])) {
                echo '<div class="error-message">
                    <p>Nenhuma imagem encontrada para o termo "' . $search_term . '".</p>
                    <p>Tente outro termo de busca ou um dos filtros rápidos acima.</p>
                </div>';
            } else {
                $items = $data['collection']['items'];
                $total_items = count($items);
                
                echo '<div class="nasa-grid">';
                
                // Exibe até 12 imagens por página
                $items_per_page = 12;
                $start = 0;
                $end = min($items_per_page, $total_items);
                
                for ($i = $start; $i < $end; $i++) {
                    if (!isset($items[$i]['links'][0]['href'])) continue;
                    
                    $img = $items[$i]['links'][0]['href'];
                    $title = $items[$i]['data'][0]['title'] ?? 'Imagem NASA';
                    $description = $items[$i]['data'][0]['description'] ?? '';
                    $date_created = isset($items[$i]['data'][0]['date_created']) ? 
                        date('d/m/Y', strtotime($items[$i]['data'][0]['date_created'])) : '';
                    
                    // Limita a descrição a 100 caracteres
                    $short_desc = strlen($description) > 100 ? 
                        substr($description, 0, 100) . '...' : $description;
                    
                    echo '<div class="nasa-item" data-full-desc="' . htmlspecialchars($description) . '">
                        <div class="nasa-image">
                            <img src="' . $img . '" alt="' . $title . '" loading="lazy" 
                                 onclick="openImageViewer(\'' . $img . '\', \'' . addslashes($title) . '\', \'' . addslashes($description) . '\', \'' . $date_created . '\')">
                        </div>
                        <div class="nasa-info">
                            <h3>' . $title . '</h3>
                            <p class="nasa-date">' . $date_created . '</p>
                            <p class="nasa-desc">' . $short_desc . '</p>
                        </div>
                    </div>';
                }
                
                echo '</div>';
                
                // Adiciona paginação se houver mais de uma página
                if ($total_items > $items_per_page) {
                    echo '<div class="pagination">
                        <a href="?q=' . urlencode($search_term) . '&page=' . max(1, $page - 1) . '" 
                           class="page-btn ' . ($page <= 1 ? 'disabled' : '') . '">
                           <i class="fas fa-chevron-left"></i> Anterior
                        </a>
                        <span class="page-info">Página ' . $page . '</span>
                        <a href="?q=' . urlencode($search_term) . '&page=' . ($page + 1) . '" 
                           class="page-btn">
                           Próxima <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>';
                }
            }
        } catch (Exception $e) {
            echo '<div class="error-message">
                <p>Erro ao acessar a API da NASA: ' . $e->getMessage() . '</p>
                <p>Tente novamente mais tarde ou use um dos filtros rápidos acima.</p>
            </div>';
        }
        ?>
    </div>
</section>

<!-- Visualizador de Imagem Modal -->
<div id="image-viewer" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <img id="modal-image" src="" alt="Imagem em tamanho completo">
        <div class="modal-info">
            <h3 id="modal-title"></h3>
            <p id="modal-date"></p>
            <p id="modal-description"></p>
        </div>
    </div>
</div>

<style>
/* Estilos para a página NASA */
.search-section {
    margin: 20px 0;
    padding: 20px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
}

.search-form {
    width: 100%;
}

.search-container {
    display: flex;
    margin-bottom: 15px;
}

.search-input {
    flex: 1;
    padding: 12px 15px;
    border-radius: 5px 0 0 5px;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 16px;
}

.search-button {
    padding: 12px 20px;
    background: #4da6ff;
    color: #000;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

.search-button:hover {
    background: #3a8ad6;
}

.quick-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}

.filter-label {
    color: #aaa;
    margin-right: 5px;
}

.filter-tag {
    display: inline-block;
    padding: 5px 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s;
}

.filter-tag:hover, .filter-tag.active {
    background: rgba(77, 166, 255, 0.5);
    transform: translateY(-2px);
}

.random-tag {
    background: rgba(255, 165, 0, 0.3);
}

.random-tag:hover {
    background: rgba(255, 165, 0, 0.5);
}

.nasa-gallery {
    margin-top: 20px;
}

.nasa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.nasa-item {
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.nasa-item:hover {
    transform: translateY(-5px);
}

.nasa-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
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
    font-size: 18px;
}

.nasa-date {
    color: #aaa;
    font-size: 14px;
    margin-bottom: 10px;
}

.nasa-desc {
    color: #ddd;
    font-size: 14px;
    line-height: 1.4;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
    gap: 15px;
}

.page-btn {
    padding: 8px 15px;
    background: rgba(77, 166, 255, 0.3);
    border-radius: 5px;
    color: #fff;
    text-decoration: none;
    transition: background 0.3s;
}

.page-btn:hover {
    background: rgba(77, 166, 255, 0.5);
}

.page-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-info {
    color: #aaa;
}

.error-message {
    background: rgba(255, 50, 50, 0.2);
    border-left: 4px solid #ff3232;
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
}

/* Modal Viewer */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    overflow: auto;
}

.modal-content {
    position: relative;
    margin: 5% auto;
    width: 80%;
    max-width: 1200px;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 20px;
    color: #fff;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1001;
}

#modal-image {
    width: 100%;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 5px;
}

.modal-info {
    background: rgba(0, 0, 0, 0.7);
    padding: 20px;
    border-radius: 0 0 5px 5px;
}

.modal-info h3 {
    margin-top: 0;
    color: #4da6ff;
}

#modal-date {
    color: #aaa;
    margin-bottom: 10px;
}

#modal-description {
    color: #ddd;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .nasa-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>

<script>
// Função para abrir o visualizador de imagem
function openImageViewer(imgSrc, title, description, date) {
    const modal = document.getElementById('image-viewer');
    const modalImg = document.getElementById('modal-image');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc = document.getElementById('modal-description');
    const modalDate = document.getElementById('modal-date');
    
    modal.style.display = 'block';
    modalImg.src = imgSrc;
    modalTitle.innerText = title;
    modalDesc.innerText = description;
    modalDate.innerText = date ? `Data: ${date}` : '';
    
    // Bloqueia o scroll da página
    document.body.style.overflow = 'hidden';
}

// Fecha o modal quando clicar no X
document.querySelector('.close-modal').addEventListener('click', function() {
    document.getElementById('image-viewer').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restaura o scroll
});

// Fecha o modal quando clicar fora da imagem
window.addEventListener('click', function(event) {
    const modal = document.getElementById('image-viewer');
    if (event.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaura o scroll
    }
});
</script>

<?php
// Função para gerar um termo de busca aleatório
function get_random_search_term() {
    $terms = [
        'galaxy', 'nebula', 'supernova', 'black hole', 'mars', 'jupiter', 
        'saturn', 'venus', 'mercury', 'moon', 'asteroid', 'comet', 
        'space shuttle', 'apollo', 'hubble', 'webb', 'telescope', 
        'milky way', 'aurora', 'eclipse', 'star', 'planet', 'cosmos'
    ];
    return $terms[array_rand($terms)];
}

// Renderiza o rodapé
render_footer();
?>
