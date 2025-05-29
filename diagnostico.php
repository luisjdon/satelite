<?php
/**
 * SatéliteVision - Página de Diagnóstico
 * Verifica o status de todas as APIs e imagens
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui os arquivos necessários
require_once 'config.php';
require_once 'includes/api_functions.php';

// Função para testar uma API e retornar o resultado
function test_api($name, $url, $params = []) {
    echo "<h3>Testando API: $name</h3>";
    echo "<p>URL: $url</p>";
    
    $start_time = microtime(true);
    $result = api_request($url, $params);
    $end_time = microtime(true);
    $execution_time = round(($end_time - $start_time) * 1000, 2); // em milissegundos
    
    echo "<p>Tempo de execução: $execution_time ms</p>";
    
    if (isset($result['error'])) {
        echo "<p style='color: red;'>Erro: " . $result['error'] . "</p>";
        return false;
    } else {
        echo "<p style='color: green;'>Sucesso!</p>";
        
        // Exibe uma amostra dos dados
        echo "<details>";
        echo "<summary>Ver amostra dos dados</summary>";
        echo "<pre>";
        if (is_array($result)) {
            if (isset($result['raw_data'])) {
                // Se for dados binários, mostra apenas o tipo e tamanho
                echo "Tipo de conteúdo: " . $result['content_type'] . "\n";
                echo "Tamanho dos dados: " . strlen($result['raw_data']) . " bytes";
            } else {
                // Limita a exibição para não sobrecarregar a página
                print_r(array_slice($result, 0, 2));
            }
        } else {
            var_dump($result);
        }
        echo "</pre>";
        echo "</details>";
        
        return $result;
    }
}

// Função para testar a existência de um diretório
function test_directory($path) {
    echo "<h3>Testando diretório: $path</h3>";
    
    if (is_dir($path)) {
        echo "<p style='color: green;'>O diretório existe!</p>";
        
        // Verifica permissões
        if (is_writable($path)) {
            echo "<p style='color: green;'>O diretório tem permissão de escrita.</p>";
        } else {
            echo "<p style='color: red;'>O diretório NÃO tem permissão de escrita!</p>";
        }
        
        return true;
    } else {
        echo "<p style='color: red;'>O diretório NÃO existe!</p>";
        
        // Tenta criar o diretório
        echo "<p>Tentando criar o diretório...</p>";
        if (mkdir($path, 0755, true)) {
            echo "<p style='color: green;'>Diretório criado com sucesso!</p>";
            return true;
        } else {
            echo "<p style='color: red;'>Falha ao criar o diretório!</p>";
            return false;
        }
    }
}

// Função para testar a existência de uma imagem
function test_image($path) {
    echo "<h3>Testando imagem: $path</h3>";
    
    if (file_exists($path)) {
        $size = filesize($path);
        $type = mime_content_type($path);
        
        echo "<p style='color: green;'>A imagem existe!</p>";
        echo "<p>Tamanho: $size bytes</p>";
        echo "<p>Tipo: $type</p>";
        
        // Exibe a imagem
        echo "<img src='$path' style='max-width: 300px; border: 1px solid #ccc;' />";
        
        return true;
    } else {
        echo "<p style='color: red;'>A imagem NÃO existe!</p>";
        return false;
    }
}

// Função para criar uma imagem de teste
function create_test_image($path) {
    echo "<h3>Criando imagem de teste: $path</h3>";
    
    // Cria o diretório se não existir
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Cria uma imagem simples
    $image = imagecreatetruecolor(300, 200);
    $bg_color = imagecolorallocate($image, 0, 0, 0);
    $text_color = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, 0, 300, 200, $bg_color);
    imagestring($image, 5, 50, 80, 'Imagem de Teste', $text_color);
    
    // Salva a imagem
    $result = imagejpeg($image, $path);
    imagedestroy($image);
    
    if ($result) {
        echo "<p style='color: green;'>Imagem criada com sucesso!</p>";
        echo "<img src='$path' style='max-width: 300px; border: 1px solid #ccc;' />";
        return true;
    } else {
        echo "<p style='color: red;'>Falha ao criar a imagem!</p>";
        return false;
    }
}

// Função para testar uma chamada JavaScript
function test_js_call($name, $url) {
    echo "<h3>Testando chamada JavaScript: $name</h3>";
    echo "<p>URL: $url</p>";
    
    echo "<div id='js-test-$name' style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>Carregando...</div>";
    
    echo "<script>
        fetch('$url')
            .then(response => {
                document.getElementById('js-test-$name').innerHTML += '<p>Status: ' + response.status + '</p>';
                return response.text();
            })
            .then(data => {
                let output = '<p style=\"color: green;\">Dados recebidos!</p>';
                output += '<details><summary>Ver dados</summary><pre>' + data.substring(0, 500) + '...</pre></details>';
                document.getElementById('js-test-$name').innerHTML += output;
            })
            .catch(error => {
                document.getElementById('js-test-$name').innerHTML += '<p style=\"color: red;\">Erro: ' + error + '</p>';
            });
    </script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico - SatéliteVision</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2 {
            color: #333;
        }
        .section {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        details {
            margin: 10px 0;
        }
        summary {
            cursor: pointer;
            padding: 5px;
            background-color: #f0f0f0;
        }
        pre {
            background-color: #f9f9f9;
            padding: 10px;
            overflow: auto;
            max-height: 300px;
        }
    </style>
</head>
<body>
    <h1>Diagnóstico - SatéliteVision</h1>
    
    <div class="section">
        <h2>Informações do Sistema</h2>
        <p>PHP Version: <?php echo phpversion(); ?></p>
        <p>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
        <p>User Agent: <?php echo $_SERVER['HTTP_USER_AGENT']; ?></p>
        
        <details>
            <summary>Extensões PHP</summary>
            <pre><?php print_r(get_loaded_extensions()); ?></pre>
        </details>
        
        <h3>Funções Críticas</h3>
        <p>cURL habilitado: <?php echo function_exists('curl_init') ? '<span style="color: green;">Sim</span>' : '<span style="color: red;">Não</span>'; ?></p>
        <p>JSON habilitado: <?php echo function_exists('json_decode') ? '<span style="color: green;">Sim</span>' : '<span style="color: red;">Não</span>'; ?></p>
        <p>GD habilitado: <?php echo function_exists('imagecreatetruecolor') ? '<span style="color: green;">Sim</span>' : '<span style="color: red;">Não</span>'; ?></p>
    </div>
    
    <div class="section">
        <h2>Teste de Diretórios</h2>
        <?php
        // Testa o diretório de cache
        test_directory($cache['directory']);
        
        // Testa os diretórios de imagens
        test_directory('img/earth');
        test_directory('img/space');
        test_directory('img/iss');
        test_directory('img/goes');
        test_directory('img/landsat');
        ?>
    </div>
    
    <div class="section">
        <h2>Teste de Imagens</h2>
        <?php
        // Testa as imagens padrão
        $default_images = [
            'img/earth/earth-default.jpg',
            'img/space/space-default.jpg',
            'img/iss/iss-default.jpg',
            'img/goes/goes-default.jpg',
            'img/landsat/landsat-sample1.jpg'
        ];
        
        foreach ($default_images as $image) {
            if (!test_image($image)) {
                // Se a imagem não existir, cria uma imagem de teste
                create_test_image($image);
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Teste de APIs</h2>
        <?php
        // Testa a API EPIC
        $epic_result = test_api('NASA EPIC', $api_urls['nasa_epic'], ['api_key' => $api_keys['nasa']]);
        
        // Se a API EPIC retornar dados, testa a URL da imagem
        if ($epic_result && !isset($epic_result['error'])) {
            if (isset($epic_result[0])) {
                $image_url = get_epic_image_url($epic_result[0]);
                echo "<h3>Testando URL da imagem EPIC</h3>";
                echo "<p>URL: $image_url</p>";
                echo "<img src='$image_url' style='max-width: 300px; border: 1px solid #ccc;' />";
            }
        }
        
        // Testa a API ISS
        test_api('ISS Location', $api_urls['iss_tracker']);
        
        // Testa a API APOD
        $apod_result = test_api('NASA APOD', $api_urls['nasa_imagery'], ['api_key' => $api_keys['nasa']]);
        
        // Se a API APOD retornar dados, testa a URL da imagem
        if ($apod_result && !isset($apod_result['error'])) {
            if (isset($apod_result['url'])) {
                echo "<h3>Testando URL da imagem APOD</h3>";
                echo "<p>URL: {$apod_result['url']}</p>";
                echo "<img src='{$apod_result['url']}' style='max-width: 300px; border: 1px solid #ccc;' />";
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Teste de Endpoints</h2>
        <?php
        // Testa os endpoints da API
        test_js_call('Earth Data', 'api/earth_data.php');
        test_js_call('Space Data', 'api/space_data.php');
        test_js_call('ISS Location', 'api/iss_location.php');
        test_js_call('Proxy', 'api/proxy.php?url=' . urlencode('http://api.open-notify.org/iss-now.json'));
        ?>
    </div>
    
    <div class="section">
        <h2>Teste do Proxy</h2>
        <h3>Imagem da Terra via Proxy</h3>
        <img src="api/proxy.php?url=<?php echo urlencode('https://epic.gsfc.nasa.gov/archive/natural/2023/01/01/png/epic_1b_20230101001634.png'); ?>" style="max-width: 300px; border: 1px solid #ccc;" />
    </div>
    
    <div class="section">
        <h2>Conclusão</h2>
        <p>Este diagnóstico ajuda a identificar problemas com as APIs, imagens e configurações do SatéliteVision.</p>
        <p>Verifique os resultados acima para identificar e corrigir os problemas.</p>
        <p><a href="index.php">Voltar para a página inicial</a></p>
    </div>
</body>
</html>
