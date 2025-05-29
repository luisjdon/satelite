<?php
/**
 * SatéliteVision - Correção de Imagens
 * Ferramenta para corrigir problemas com imagens
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o arquivo de configuração
require_once 'config.php';
require_once 'includes/api_functions.php';
require_once 'includes/layout_functions.php';

// Renderiza o cabeçalho
render_header('Correção de Imagens', 'Ferramenta para corrigir problemas com imagens');

// Função para criar diretórios necessários
function criar_diretorios() {
    $diretorios = [
        'img',
        'img/earth',
        'img/space',
        'img/iss',
        'cache'
    ];
    
    $resultados = [];
    
    foreach ($diretorios as $diretorio) {
        $caminho_completo = __DIR__ . '/' . $diretorio;
        $existe = is_dir($caminho_completo);
        
        // Tenta criar o diretório se não existir
        if (!$existe) {
            $criado = @mkdir($caminho_completo, 0755, true);
        } else {
            $criado = true;
        }
        
        // Tenta corrigir permissões
        $corrigido = @chmod($caminho_completo, 0755);
        
        $resultados[] = [
            'diretorio' => $diretorio,
            'existe' => $existe || $criado,
            'criado' => !$existe && $criado,
            'corrigido' => $corrigido
        ];
    }
    
    return $resultados;
}

// Função para criar imagens padrão
function criar_imagens_padrao() {
    $imagens = [
        'img/earth/earth-default.jpg' => [800, 600, [0, 0, 128], 'Terra - Imagem Padrão'],
        'img/earth/earth-south-america.jpg' => [800, 600, [0, 64, 128], 'Terra - América do Sul'],
        'img/earth/earth-north-america.jpg' => [800, 600, [0, 128, 128], 'Terra - América do Norte'],
        'img/space/space-default.jpg' => [800, 600, [0, 0, 64], 'Espaço - Imagem Padrão'],
        'img/iss/iss.jpg' => [800, 600, [64, 64, 64], 'Estação Espacial Internacional']
    ];
    
    $resultados = [];
    
    foreach ($imagens as $caminho => $config) {
        list($largura, $altura, $cor, $texto) = $config;
        
        // Verifica se o diretório existe
        $diretorio = dirname($caminho);
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }
        
        // Cria a imagem se não existir ou for muito pequena
        if (!file_exists($caminho) || filesize($caminho) < 1000) {
            // Verifica se a extensão GD está disponível
            if (function_exists('imagecreatetruecolor')) {
                $imagem = imagecreatetruecolor($largura, $altura);
                $cor_fundo = imagecolorallocate($imagem, $cor[0], $cor[1], $cor[2]);
                imagefill($imagem, 0, 0, $cor_fundo);
                
                // Adiciona texto à imagem
                $cor_texto = imagecolorallocate($imagem, 255, 255, 255);
                $fonte = 5; // Fonte padrão do GD
                
                // Centraliza o texto
                $largura_texto = imagefontwidth($fonte) * strlen($texto);
                $altura_texto = imagefontheight($fonte);
                $x = ($largura - $largura_texto) / 2;
                $y = ($altura - $altura_texto) / 2;
                
                imagestring($imagem, $fonte, $x, $y, $texto, $cor_texto);
                
                // Salva a imagem
                imagejpeg($imagem, $caminho, 90);
                imagedestroy($imagem);
                
                $criada = true;
            } else {
                // Se a extensão GD não estiver disponível, cria um arquivo de texto
                file_put_contents($caminho . '.txt', $texto);
                $criada = false;
            }
        } else {
            $criada = false;
        }
        
        $resultados[] = [
            'caminho' => $caminho,
            'existe' => file_exists($caminho),
            'tamanho' => file_exists($caminho) ? filesize($caminho) : 0,
            'criada' => $criada
        ];
    }
    
    return $resultados;
}

// Função para verificar se o arquivo imagens_diretas.php existe
function verificar_api_direta() {
    $caminho = __DIR__ . '/api/imagens_diretas.php';
    $existe = file_exists($caminho);
    
    // Se não existir, cria o arquivo
    if (!$existe) {
        $conteudo = file_get_contents(__DIR__ . '/api/imagens_diretas.php.txt');
        if (!$conteudo) {
            $conteudo = '<?php
/**
 * SatéliteVision - API de Imagens Diretas
 * Fornece imagens diretas dos satélites sem depender de APIs externas
 */

// Habilita exibição de erros para diagnóstico
ini_set(\'display_errors\', 1);
ini_set(\'display_startup_errors\', 1);
error_reporting(E_ALL);

// Inclui o arquivo de configuração
require_once __DIR__ . \'/../config.php\';

// Define o cabeçalho como JSON
header(\'Content-Type: application/json\');

// Função para obter a data atual formatada
function get_formatted_date() {
    return date(\'d/m/Y H:i:s\');
}

// Função para obter imagens da Terra
function get_earth_images() {
    // URLs de imagens da Terra em alta resolução
    $earth_images = [
        [
            \'id\' => \'earth-1\',
            \'title\' => \'Terra vista do Espaço - Hemisfério Ocidental\',
            \'description\' => \'Imagem da Terra mostrando as Américas e o Oceano Pacífico\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://epic.gsfc.nasa.gov/archive/natural/2022/12/25/png/epic_1b_20221225003633.png\',
            \'centroid_coordinates\' => [
                \'lat\' => 0.0,
                \'lon\' => -90.0
            ]
        ],
        [
            \'id\' => \'earth-2\',
            \'title\' => \'Terra vista do Espaço - Hemisfério Oriental\',
            \'description\' => \'Imagem da Terra mostrando a África, Europa, Ásia e Oceania\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://epic.gsfc.nasa.gov/archive/natural/2022/12/25/png/epic_1b_20221225073633.png\',
            \'centroid_coordinates\' => [
                \'lat\' => 0.0,
                \'lon\' => 90.0
            ]
        ],
        [
            \'id\' => \'earth-3\',
            \'title\' => \'Terra vista do Espaço - Polo Norte\',
            \'description\' => \'Imagem da Terra mostrando o Polo Norte e o Ártico\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://epic.gsfc.nasa.gov/archive/natural/2022/06/21/png/epic_1b_20220621003633.png\',
            \'centroid_coordinates\' => [
                \'lat\' => 90.0,
                \'lon\' => 0.0
            ]
        ],
        [
            \'id\' => \'earth-4\',
            \'title\' => \'Terra vista do Espaço - Polo Sul\',
            \'description\' => \'Imagem da Terra mostrando o Polo Sul e a Antártida\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://epic.gsfc.nasa.gov/archive/natural/2022/12/21/png/epic_1b_20221221003633.png\',
            \'centroid_coordinates\' => [
                \'lat\' => -90.0,
                \'lon\' => 0.0
            ]
        ]
    ];
    
    return $earth_images;
}

// Função para obter imagens do espaço
function get_space_images() {
    // URLs de imagens do espaço em alta resolução
    $space_images = [
        [
            \'id\' => \'space-1\',
            \'title\' => \'Nebulosa de Órion\',
            \'description\' => \'A Nebulosa de Órion (também conhecida como Messier 42, M42 ou NGC 1976) é uma nebulosa difusa situada na constelação de Órion.\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://apod.nasa.gov/apod/image/2201/OrionStarFree3_Harbison_5000.jpg\',
            \'media_type\' => \'image\',
            \'copyright\' => \'NASA/ESA\'
        ],
        [
            \'id\' => \'space-2\',
            \'title\' => \'Galáxia Andrômeda\',
            \'description\' => \'A Galáxia de Andrômeda (Messier 31, NGC 224) é uma galáxia espiral localizada a cerca de 2,54 milhões de anos-luz da Terra.\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://apod.nasa.gov/apod/image/2211/M31_HubbleSpitzer_1080.jpg\',
            \'media_type\' => \'image\',
            \'copyright\' => \'NASA/ESA\'
        ],
        [
            \'id\' => \'space-3\',
            \'title\' => \'Nebulosa do Caranguejo\',
            \'description\' => \'A Nebulosa do Caranguejo (Messier 1, NGC 1952) é um remanescente de supernova e nebulosa de vento pulsar localizada na constelação de Touro.\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://apod.nasa.gov/apod/image/1912/M1_HubbleChandra_4000.jpg\',
            \'media_type\' => \'image\',
            \'copyright\' => \'NASA/ESA\'
        ],
        [
            \'id\' => \'space-4\',
            \'title\' => \'Pilares da Criação\',
            \'description\' => \'Os Pilares da Criação são formações de gás e poeira dentro da Nebulosa da Águia, a aproximadamente 7.000 anos-luz da Terra.\',
            \'date\' => get_formatted_date(),
            \'url\' => \'https://apod.nasa.gov/apod/image/1901/pillars_creation.jpg\',
            \'media_type\' => \'image\',
            \'copyright\' => \'NASA/ESA\'
        ]
    ];
    
    return $space_images;
}

// Função para obter a localização da ISS
function get_iss_location() {
    // Localização simulada da ISS
    $iss_location = [
        \'message\' => \'success\',
        \'timestamp\' => time(),
        \'iss_position\' => [
            \'latitude\' => rand(-80, 80) + (rand(0, 1000) / 1000),
            \'longitude\' => rand(-180, 180) + (rand(0, 1000) / 1000)
        ],
        \'info\' => [
            \'altitude\' => \'408 km\',
            \'velocity\' => \'27.600 km/h\',
            \'orbit_time\' => \'92 minutos\',
            \'last_update\' => get_formatted_date()
        ]
    ];
    
    return $iss_location;
}

// Determina o tipo de dados a retornar
$type = isset($_GET[\'type\']) ? $_GET[\'type\'] : \'all\';

// Prepara a resposta
$response = [
    \'status\' => \'success\',
    \'timestamp\' => get_formatted_date()
];

// Adiciona os dados solicitados à resposta
switch ($type) {
    case \'earth\':
        $response[\'earth_images\'] = get_earth_images();
        break;
    case \'space\':
        $response[\'space_images\'] = get_space_images();
        break;
    case \'iss\':
        $response[\'iss_data\'] = get_iss_location();
        break;
    default:
        $response[\'earth_images\'] = get_earth_images();
        $response[\'space_images\'] = get_space_images();
        $response[\'iss_data\'] = get_iss_location();
        break;
}

// Retorna a resposta em formato JSON
echo json_encode($response, JSON_PRETTY_PRINT);';
        }
        
        file_put_contents($caminho, $conteudo);
        $criado = file_exists($caminho);
    } else {
        $criado = false;
    }
    
    return [
        'caminho' => $caminho,
        'existe' => $existe || $criado,
        'criado' => $criado
    ];
}

// Função para verificar e atualizar o arquivo main.js
function verificar_main_js() {
    $caminho = __DIR__ . '/js/main.js';
    $existe = file_exists($caminho);
    $atualizado = false;
    
    if ($existe) {
        $conteudo = file_get_contents($caminho);
        
        // Verifica se já está usando a API direta
        if (strpos($conteudo, 'api/imagens_diretas.php') === false) {
            // Substitui as chamadas de API antigas pelas novas
            $conteudo = str_replace("fetch('api/earth_data.php')", "fetch('api/imagens_diretas.php?type=earth')", $conteudo);
            $conteudo = str_replace("fetch('api/space_data.php')", "fetch('api/imagens_diretas.php?type=space')", $conteudo);
            $conteudo = str_replace("fetch('api/iss_location.php')", "fetch('api/imagens_diretas.php?type=iss')", $conteudo);
            
            // Salva o arquivo atualizado
            file_put_contents($caminho, $conteudo);
            $atualizado = true;
        }
    }
    
    return [
        'caminho' => $caminho,
        'existe' => $existe,
        'atualizado' => $atualizado
    ];
}

// Função para verificar e criar o arquivo iss_map.js
function verificar_iss_map_js() {
    $caminho = __DIR__ . '/js/iss_map.js';
    $existe = file_exists($caminho);
    
    // Se não existir, cria o arquivo
    if (!$existe) {
        $conteudo = '/**
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
    const mapElement = document.getElementById(\'iss-map\');
    if (!mapElement) return;
    
    // Inicializa o mapa Leaflet
    issMap = L.map(\'iss-map\').setView([0, 0], 2);
    
    // Adiciona o mapa base
    L.tileLayer(\'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\', {
        attribution: \'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors\',
        maxZoom: 18
    }).addTo(issMap);
    
    // Adiciona o caminho da ISS
    issPath = L.polyline([], {
        color: \'#00f7ff\',
        weight: 2,
        opacity: 0.7
    }).addTo(issMap);
    
    // Inicializa o array de pontos do caminho
    issPathPoints = [];
    
    console.log(\'Mapa da ISS inicializado com sucesso!\');
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
            className: \'iss-icon\',
            html: \'<i class="fas fa-satellite"></i>\',
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
}';
        
        file_put_contents($caminho, $conteudo);
        $criado = file_exists($caminho);
    } else {
        $criado = false;
    }
    
    return [
        'caminho' => $caminho,
        'existe' => $existe || $criado,
        'criado' => $criado
    ];
}

// Executa as correções
$diretorios = criar_diretorios();
$imagens = criar_imagens_padrao();
$api_direta = verificar_api_direta();
$main_js = verificar_main_js();
$iss_map_js = verificar_iss_map_js();

// Verifica se todas as correções foram aplicadas
$todas_correcoes = true;
foreach ($diretorios as $diretorio) {
    if (!$diretorio['existe']) {
        $todas_correcoes = false;
        break;
    }
}

foreach ($imagens as $imagem) {
    if (!$imagem['existe']) {
        $todas_correcoes = false;
        break;
    }
}

if (!$api_direta['existe'] || !$main_js['existe'] || !$iss_map_js['existe']) {
    $todas_correcoes = false;
}

?>

<div class="container">
    <h1 class="page-title">Correção de Imagens</h1>
    <p class="page-description">Esta ferramenta corrige problemas com imagens no SatéliteVision.</p>
    
    <?php if ($todas_correcoes): ?>
    <div class="success-message">
        <h2><i class="fas fa-check-circle"></i> Todas as correções foram aplicadas com sucesso!</h2>
        <p>O site agora deve exibir todas as imagens corretamente. Você pode voltar para a página inicial e verificar.</p>
    </div>
    <?php else: ?>
    <div class="warning-message">
        <h2><i class="fas fa-exclamation-triangle"></i> Algumas correções não puderam ser aplicadas</h2>
        <p>Verifique os detalhes abaixo para mais informações.</p>
    </div>
    <?php endif; ?>
    
    <div class="correction-section">
        <h2>1. Diretórios</h2>
        <div class="correction-result">
            <table class="correction-table">
                <tr>
                    <th>Diretório</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
                <?php foreach ($diretorios as $diretorio): ?>
                <tr>
                    <td><?php echo htmlspecialchars($diretorio['diretorio']); ?></td>
                    <td class="<?php echo $diretorio['existe'] ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $diretorio['existe'] ? 'OK' : 'Erro'; ?>
                    </td>
                    <td>
                        <?php if ($diretorio['criado']): ?>
                        Diretório criado
                        <?php elseif ($diretorio['existe']): ?>
                        Diretório já existia
                        <?php else: ?>
                        Falha ao criar diretório
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <div class="correction-section">
        <h2>2. Imagens Padrão</h2>
        <div class="correction-result">
            <table class="correction-table">
                <tr>
                    <th>Imagem</th>
                    <th>Status</th>
                    <th>Tamanho</th>
                    <th>Ação</th>
                </tr>
                <?php foreach ($imagens as $imagem): ?>
                <tr>
                    <td><?php echo htmlspecialchars($imagem['caminho']); ?></td>
                    <td class="<?php echo $imagem['existe'] ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $imagem['existe'] ? 'OK' : 'Erro'; ?>
                    </td>
                    <td><?php echo $imagem['tamanho']; ?> bytes</td>
                    <td>
                        <?php if ($imagem['criada']): ?>
                        Imagem criada
                        <?php elseif ($imagem['existe']): ?>
                        Imagem já existia
                        <?php else: ?>
                        Falha ao criar imagem
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <div class="correction-section">
        <h2>3. API Direta</h2>
        <div class="correction-result">
            <table class="correction-table">
                <tr>
                    <th>Arquivo</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($api_direta['caminho']); ?></td>
                    <td class="<?php echo $api_direta['existe'] ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $api_direta['existe'] ? 'OK' : 'Erro'; ?>
                    </td>
                    <td>
                        <?php if ($api_direta['criado']): ?>
                        Arquivo criado
                        <?php elseif ($api_direta['existe']): ?>
                        Arquivo já existia
                        <?php else: ?>
                        Falha ao criar arquivo
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="correction-section">
        <h2>4. JavaScript</h2>
        <div class="correction-result">
            <table class="correction-table">
                <tr>
                    <th>Arquivo</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($main_js['caminho']); ?></td>
                    <td class="<?php echo $main_js['existe'] ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $main_js['existe'] ? 'OK' : 'Erro'; ?>
                    </td>
                    <td>
                        <?php if ($main_js['atualizado']): ?>
                        Arquivo atualizado
                        <?php elseif ($main_js['existe']): ?>
                        Arquivo já estava atualizado
                        <?php else: ?>
                        Arquivo não encontrado
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($iss_map_js['caminho']); ?></td>
                    <td class="<?php echo $iss_map_js['existe'] ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $iss_map_js['existe'] ? 'OK' : 'Erro'; ?>
                    </td>
                    <td>
                        <?php if ($iss_map_js['criado']): ?>
                        Arquivo criado
                        <?php elseif ($iss_map_js['existe']): ?>
                        Arquivo já existia
                        <?php else: ?>
                        Falha ao criar arquivo
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="index.php" class="btn btn-primary">Voltar para a Página Inicial</a>
        <a href="corrigir_imagens.php" class="btn btn-secondary">Executar Novamente</a>
    </div>
</div>

<style>
.correction-section {
    margin-bottom: 30px;
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 10px;
    padding: 20px;
}

.correction-result {
    margin-top: 15px;
}

.correction-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.correction-table th, .correction-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #444;
}

.correction-table th {
    background-color: rgba(0, 0, 0, 0.5);
}

.status-ok {
    color: #4CAF50;
    font-weight: bold;
}

.status-error {
    color: #F44336;
    font-weight: bold;
}

.success-message {
    background-color: rgba(76, 175, 80, 0.2);
    border-left: 5px solid #4CAF50;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 5px;
}

.warning-message {
    background-color: rgba(255, 152, 0, 0.2);
    border-left: 5px solid #FF9800;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 5px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}
</style>

<?php
// Renderiza o rodapé
render_footer();
?>
