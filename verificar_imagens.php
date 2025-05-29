<?php
/**
 * SatéliteVision - Verificador de Imagens
 * Ferramenta para verificar e corrigir problemas com imagens
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
render_header('Verificador de Imagens', 'Ferramenta para verificar e corrigir problemas com imagens');

// Função para verificar se uma URL de imagem está acessível
function verificar_imagem($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    $status = ($http_code == 200 && strpos($content_type, 'image/') !== false);
    
    return [
        'url' => $url,
        'status' => $status,
        'http_code' => $http_code,
        'content_type' => $content_type
    ];
}

// Verifica as imagens da API direta
function verificar_api_direta() {
    $api_url = 'api/imagens_diretas.php';
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);
    
    $resultados = [
        'status' => !empty($data),
        'earth_images' => [],
        'space_images' => []
    ];
    
    if (!empty($data['earth_images'])) {
        foreach ($data['earth_images'] as $image) {
            $resultados['earth_images'][] = verificar_imagem($image['url']);
        }
    }
    
    if (!empty($data['space_images'])) {
        foreach ($data['space_images'] as $image) {
            $resultados['space_images'][] = verificar_imagem($image['url']);
        }
    }
    
    return $resultados;
}

// Verifica as imagens estáticas
function verificar_imagens_estaticas() {
    $diretorios = [
        'img/earth',
        'img/space',
        'img/iss'
    ];
    
    $resultados = [];
    
    foreach ($diretorios as $diretorio) {
        if (is_dir($diretorio)) {
            $arquivos = scandir($diretorio);
            foreach ($arquivos as $arquivo) {
                if ($arquivo != '.' && $arquivo != '..' && !is_dir($diretorio . '/' . $arquivo)) {
                    $caminho = $diretorio . '/' . $arquivo;
                    $resultados[] = [
                        'caminho' => $caminho,
                        'existe' => file_exists($caminho),
                        'tamanho' => file_exists($caminho) ? filesize($caminho) : 0,
                        'tipo' => file_exists($caminho) ? mime_content_type($caminho) : 'desconhecido'
                    ];
                }
            }
        } else {
            $resultados[] = [
                'caminho' => $diretorio,
                'existe' => false,
                'tamanho' => 0,
                'tipo' => 'diretório não encontrado'
            ];
        }
    }
    
    return $resultados;
}

// Verifica e corrige problemas de permissão
function verificar_permissoes() {
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
        $permissao = $existe ? substr(sprintf('%o', fileperms($caminho_completo)), -4) : 'N/A';
        $gravavel = $existe ? is_writable($caminho_completo) : false;
        
        // Tenta criar o diretório se não existir
        if (!$existe) {
            $criado = @mkdir($caminho_completo, 0755, true);
        } else {
            $criado = true;
        }
        
        // Tenta corrigir permissões se não for gravável
        if ($existe && !$gravavel) {
            $corrigido = @chmod($caminho_completo, 0755);
        } else {
            $corrigido = true;
        }
        
        $resultados[] = [
            'diretorio' => $diretorio,
            'existe' => $existe,
            'permissao' => $permissao,
            'gravavel' => $gravavel,
            'criado' => $criado,
            'corrigido' => $corrigido
        ];
    }
    
    return $resultados;
}

// Cria imagens padrão se não existirem
function criar_imagens_padrao() {
    $imagens = [
        'img/earth/earth-default.jpg' => [800, 600, [0, 0, 128]],
        'img/earth/earth-south-america.jpg' => [800, 600, [0, 64, 128]],
        'img/earth/earth-north-america.jpg' => [800, 600, [0, 128, 128]],
        'img/space/space-default.jpg' => [800, 600, [0, 0, 64]],
        'img/iss/iss.jpg' => [800, 600, [64, 64, 64]]
    ];
    
    $resultados = [];
    
    foreach ($imagens as $caminho => $config) {
        list($largura, $altura, $cor) = $config;
        
        // Verifica se o diretório existe
        $diretorio = dirname($caminho);
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }
        
        // Cria a imagem se não existir ou for muito pequena
        if (!file_exists($caminho) || filesize($caminho) < 1000) {
            $imagem = imagecreatetruecolor($largura, $altura);
            $cor_fundo = imagecolorallocate($imagem, $cor[0], $cor[1], $cor[2]);
            imagefill($imagem, 0, 0, $cor_fundo);
            
            // Adiciona texto à imagem
            $cor_texto = imagecolorallocate($imagem, 255, 255, 255);
            $texto = basename($caminho, '.jpg');
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

// Executa as verificações
$api_direta = verificar_api_direta();
$imagens_estaticas = verificar_imagens_estaticas();
$permissoes = verificar_permissoes();
$imagens_padrao = criar_imagens_padrao();

?>

<div class="container">
    <h1 class="page-title">Verificador de Imagens</h1>
    <p class="page-description">Esta ferramenta verifica e corrige problemas com imagens no SatéliteVision.</p>
    
    <div class="diagnostic-section">
        <h2>1. Verificação da API Direta</h2>
        <div class="diagnostic-result">
            <p>Status da API: <span class="<?php echo $api_direta['status'] ? 'status-ok' : 'status-error'; ?>"><?php echo $api_direta['status'] ? 'OK' : 'Erro'; ?></span></p>
            
            <h3>Imagens da Terra:</h3>
            <table class="diagnostic-table">
                <tr>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Código HTTP</th>
                    <th>Tipo de Conteúdo</th>
                    <th>Visualizar</th>
                </tr>
                <?php foreach ($api_direta['earth_images'] as $imagem): ?>
                <tr>
                    <td><?php echo htmlspecialchars($imagem['url']); ?></td>
                    <td class="<?php echo $imagem['status'] ? 'status-ok' : 'status-error'; ?>"><?php echo $imagem['status'] ? 'OK' : 'Erro'; ?></td>
                    <td><?php echo $imagem['http_code']; ?></td>
                    <td><?php echo htmlspecialchars($imagem['content_type']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($imagem['url']); ?>" target="_blank">Visualizar</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <h3>Imagens do Espaço:</h3>
            <table class="diagnostic-table">
                <tr>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Código HTTP</th>
                    <th>Tipo de Conteúdo</th>
                    <th>Visualizar</th>
                </tr>
                <?php foreach ($api_direta['space_images'] as $imagem): ?>
                <tr>
                    <td><?php echo htmlspecialchars($imagem['url']); ?></td>
                    <td class="<?php echo $imagem['status'] ? 'status-ok' : 'status-error'; ?>"><?php echo $imagem['status'] ? 'OK' : 'Erro'; ?></td>
                    <td><?php echo $imagem['http_code']; ?></td>
                    <td><?php echo htmlspecialchars($imagem['content_type']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($imagem['url']); ?>" target="_blank">Visualizar</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <div class="diagnostic-section">
        <h2>2. Verificação de Imagens Estáticas</h2>
        <div class="diagnostic-result">
            <table class="diagnostic-table">
                <tr>
                    <th>Caminho</th>
                    <th>Existe</th>
                    <th>Tamanho</th>
                    <th>Tipo</th>
                    <th>Visualizar</th>
                </tr>
                <?php foreach ($imagens_estaticas as $imagem): ?>
                <tr>
                    <td><?php echo htmlspecialchars($imagem['caminho']); ?></td>
                    <td class="<?php echo $imagem['existe'] ? 'status-ok' : 'status-error'; ?>"><?php echo $imagem['existe'] ? 'Sim' : 'Não'; ?></td>
                    <td><?php echo $imagem['tamanho']; ?> bytes</td>
                    <td><?php echo htmlspecialchars($imagem['tipo']); ?></td>
                    <td>
                        <?php if ($imagem['existe'] && strpos($imagem['tipo'], 'image/') !== false): ?>
                        <a href="<?php echo htmlspecialchars($imagem['caminho']); ?>" target="_blank">Visualizar</a>
                        <?php else: ?>
                        N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <div class="diagnostic-section">
        <h2>3. Verificação de Permissões</h2>
        <div class="diagnostic-result">
            <table class="diagnostic-table">
                <tr>
                    <th>Diretório</th>
                    <th>Existe</th>
                    <th>Permissão</th>
                    <th>Gravável</th>
                    <th>Ação</th>
                </tr>
                <?php foreach ($permissoes as $permissao): ?>
                <tr>
                    <td><?php echo htmlspecialchars($permissao['diretorio']); ?></td>
                    <td class="<?php echo $permissao['existe'] ? 'status-ok' : 'status-error'; ?>"><?php echo $permissao['existe'] ? 'Sim' : 'Não'; ?></td>
                    <td><?php echo $permissao['permissao']; ?></td>
                    <td class="<?php echo $permissao['gravavel'] ? 'status-ok' : 'status-error'; ?>"><?php echo $permissao['gravavel'] ? 'Sim' : 'Não'; ?></td>
                    <td>
                        <?php if (!$permissao['existe']): ?>
                        <?php echo $permissao['criado'] ? 'Diretório criado' : 'Falha ao criar diretório'; ?>
                        <?php elseif (!$permissao['gravavel']): ?>
                        <?php echo $permissao['corrigido'] ? 'Permissões corrigidas' : 'Falha ao corrigir permissões'; ?>
                        <?php else: ?>
                        OK
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <div class="diagnostic-section">
        <h2>4. Criação de Imagens Padrão</h2>
        <div class="diagnostic-result">
            <table class="diagnostic-table">
                <tr>
                    <th>Caminho</th>
                    <th>Existe</th>
                    <th>Tamanho</th>
                    <th>Criada</th>
                    <th>Visualizar</th>
                </tr>
                <?php foreach ($imagens_padrao as $imagem): ?>
                <tr>
                    <td><?php echo htmlspecialchars($imagem['caminho']); ?></td>
                    <td class="<?php echo $imagem['existe'] ? 'status-ok' : 'status-error'; ?>"><?php echo $imagem['existe'] ? 'Sim' : 'Não'; ?></td>
                    <td><?php echo $imagem['tamanho']; ?> bytes</td>
                    <td><?php echo $imagem['criada'] ? 'Sim (nova)' : 'Não (já existia)'; ?></td>
                    <td>
                        <?php if ($imagem['existe']): ?>
                        <a href="<?php echo htmlspecialchars($imagem['caminho']); ?>" target="_blank">Visualizar</a>
                        <?php else: ?>
                        N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <div class="diagnostic-section">
        <h2>5. Teste de Imagens</h2>
        <div class="diagnostic-result">
            <h3>Imagens da Terra:</h3>
            <div class="image-test-grid">
                <?php foreach ($api_direta['earth_images'] as $imagem): ?>
                <div class="image-test-item">
                    <h4><?php echo htmlspecialchars(basename($imagem['url'])); ?></h4>
                    <img src="<?php echo htmlspecialchars($imagem['url']); ?>" alt="Teste de imagem" onerror="this.src='img/earth/earth-default.jpg'; this.classList.add('fallback');">
                    <p class="<?php echo $imagem['status'] ? 'status-ok' : 'status-error'; ?>"><?php echo $imagem['status'] ? 'OK' : 'Usando fallback'; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            
            <h3>Imagens do Espaço:</h3>
            <div class="image-test-grid">
                <?php foreach ($api_direta['space_images'] as $imagem): ?>
                <div class="image-test-item">
                    <h4><?php echo htmlspecialchars(basename($imagem['url'])); ?></h4>
                    <img src="<?php echo htmlspecialchars($imagem['url']); ?>" alt="Teste de imagem" onerror="this.src='img/space/space-default.jpg'; this.classList.add('fallback');">
                    <p class="<?php echo $imagem['status'] ? 'status-ok' : 'status-error'; ?>"><?php echo $imagem['status'] ? 'OK' : 'Usando fallback'; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            
            <h3>Imagens Estáticas:</h3>
            <div class="image-test-grid">
                <?php foreach ($imagens_padrao as $imagem): ?>
                <?php if ($imagem['existe']): ?>
                <div class="image-test-item">
                    <h4><?php echo htmlspecialchars(basename($imagem['caminho'])); ?></h4>
                    <img src="<?php echo htmlspecialchars($imagem['caminho']); ?>" alt="Teste de imagem">
                    <p class="status-ok">OK</p>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="index.php" class="btn btn-primary">Voltar para a Página Inicial</a>
        <a href="verificar_imagens.php" class="btn btn-secondary">Executar Novamente</a>
    </div>
</div>

<style>
.diagnostic-section {
    margin-bottom: 30px;
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 10px;
    padding: 20px;
}

.diagnostic-result {
    margin-top: 15px;
}

.diagnostic-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.diagnostic-table th, .diagnostic-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #444;
}

.diagnostic-table th {
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

.image-test-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.image-test-item {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.image-test-item img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 10px;
}

.image-test-item img.fallback {
    border: 2px solid #F44336;
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
