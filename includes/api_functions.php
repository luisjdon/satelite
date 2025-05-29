<?php
/**
 * Funções para comunicação com APIs de satélites
 */

// Verifica se o arquivo de configuração foi carregado
if (!defined('SATELITE_CONFIG_LOADED')) {
    require_once __DIR__ . '/../config.php';
}

/**
 * Função para fazer requisições HTTP às APIs
 * 
 * @param string $url URL da API
 * @param array $params Parâmetros da requisição
 * @param string $method Método HTTP (GET, POST)
 * @return array Resposta da API em formato de array
 */
function api_request($url, $params = [], $method = 'GET') {
    global $api_keys, $cache;
    
    // Habilita exibição de erros para diagnóstico
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Gera uma chave única para o cache
    $cache_key = md5($url . json_encode($params) . $method);
    $cache_file = $cache['directory'] . '/' . $cache_key . '.json';
    
    // Verifica se existe cache válido
    if ($cache['enabled'] && file_exists($cache_file)) {
        $file_time = filemtime($cache_file);
        if (time() - $file_time < $cache['duration']) {
            try {
                $cached_data = json_decode(file_get_contents($cache_file), true);
                if ($cached_data !== null) {
                    return $cached_data;
                }
            } catch (Exception $e) {
                // Se houver erro ao ler o cache, continua com a requisição
                error_log("Erro ao ler cache: " . $e->getMessage());
            }
        }
    }
    
    // Prepara a URL com os parâmetros
    if ($method == 'GET' && !empty($params)) {
        $url .= (strpos($url, '?') === false) ? '?' : '&';
        $url .= http_build_query($params);
    }
    
    // Define um timeout para evitar esperas muito longas
    $timeout = 30; // segundos - aumentado para dar mais tempo às APIs
    
    // Inicializa o cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, 'SateliteVision/1.0');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue redirecionamentos
    
    // Configura o método POST se necessário
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    
    // Executa a requisição
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    // Log para debug
    error_log("API Request: $url");
    error_log("HTTP Code: $http_code");
    error_log("Content-Type: $content_type");
    if ($error) {
        error_log("cURL Error: $error");
    }
    
    // Verifica se houve erro ou código HTTP inválido
    if ($error) {
        return ['error' => "Erro cURL: $error"];
    }
    
    if ($http_code >= 400) {
        return ['error' => "Erro HTTP: $http_code"];
    }
    
    // Verifica se a resposta está vazia
    if (empty($response)) {
        return ['error' => 'Resposta vazia da API'];
    }
    
    // Decodifica a resposta se for JSON
    if (strpos($content_type, 'application/json') !== false || strpos($content_type, 'text/javascript') !== false) {
        $data = json_decode($response, true);
        
        // Verifica se a decodificação foi bem-sucedida
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Error: " . json_last_error_msg());
            error_log("Response: " . substr($response, 0, 1000)); // Log dos primeiros 1000 caracteres
            return ['error' => 'Erro ao decodificar JSON: ' . json_last_error_msg()];
        }
    } else {
        // Se não for JSON, retorna os dados brutos
        $data = ['raw_data' => $response, 'content_type' => $content_type];
    }
    
    // Salva no cache se estiver habilitado
    if ($cache['enabled']) {
        try {
            if (!is_dir($cache['directory'])) {
                mkdir($cache['directory'], 0755, true);
            }
            file_put_contents($cache_file, $response);
        } catch (Exception $e) {
            error_log("Erro ao salvar cache: " . $e->getMessage());
        }
    }
    
    return $data;
}

/**
 * Obtém imagens da Terra do NASA EPIC
 * 
 * @param string $date Data no formato YYYY-MM-DD (opcional)
 * @return array Dados das imagens
 */
function get_nasa_epic_images($date = null) {
    global $api_urls, $api_keys;
    
    $url = $api_urls['nasa_epic'];
    $params = ['api_key' => $api_keys['nasa']];
    
    if ($date) {
        $url .= '/date/' . $date;
    }
    
    try {
        $data = api_request($url, $params);
        
        // Verifica se a resposta é válida
        if (empty($data) || isset($data['error'])) {
            // Retorna dados simulados em caso de erro
            return [
                [
                    'identifier' => 'static-1',
                    'date' => date('Y-m-d H:i:s'),
                    'image' => 'static-1',
                    'centroid_coordinates' => [
                        'lat' => 0,
                        'lon' => 0
                    ]
                ]
            ];
        }
        
        return $data;
    } catch (Exception $e) {
        // Retorna dados simulados em caso de exceção
        return [
            [
                'identifier' => 'static-2',
                'date' => date('Y-m-d H:i:s'),
                'image' => 'static-2',
                'centroid_coordinates' => [
                    'lat' => 0,
                    'lon' => 0
                ]
            ]
        ];
    }
}

/**
 * Obtém a URL da imagem EPIC com base nos dados retornados
 * 
 * @param array $image_data Dados da imagem do EPIC
 * @return string URL da imagem
 */
function get_epic_image_url($image_data) {
    // Verifica se os dados necessários existem
    if (!isset($image_data['date']) || !isset($image_data['image'])) {
        error_log('Dados da imagem EPIC incompletos');
        return 'img/earth/earth-default.jpg';
    }
    
    // Extrai a data da imagem
    $date_parts = explode(' ', $image_data['date']);
    $date = $date_parts[0]; // Pega apenas a parte da data (YYYY-MM-DD)
    $parts = explode('-', $date);
    
    // Verifica se a data está no formato correto
    if (count($parts) !== 3) {
        error_log('Formato de data EPIC inválido: ' . $date);
        return 'img/earth/earth-default.jpg';
    }
    
    $year = $parts[0];
    $month = $parts[1];
    $day = $parts[2];
    
    // Constrói a URL da imagem
    $base_url = 'https://epic.gsfc.nasa.gov/archive/natural';
    $image_name = $image_data['image'];
    $url = "{$base_url}/{$year}/{$month}/{$day}/png/{$image_name}.png";
    
    error_log("URL da imagem EPIC: $url");
    return $url;
}

/**
 * Obtém a imagem astronômica do dia da NASA
 * 
 * @return array Dados da imagem
 */
function get_nasa_apod() {
    global $api_urls, $api_keys;
    
    $params = ['api_key' => $api_keys['nasa']];
    return api_request($api_urls['nasa_imagery'], $params);
}

/**
 * Obtém a localização atual da Estação Espacial Internacional
 * 
 * @return array Dados de localização
 */
function get_iss_location() {
    global $api_urls;
    
    return api_request($api_urls['iss_tracker']);
}

/**
 * Obtém eventos naturais na Terra do EONET
 * 
 * @param int $limit Número máximo de eventos
 * @return array Dados dos eventos
 */
function get_earth_events($limit = 10) {
    global $api_urls;
    
    $params = ['limit' => $limit];
    return api_request($api_urls['eonet'], $params);
}

/**
 * Obtém imagens do satélite GOES
 * 
 * @return array Dados das imagens
 */
function get_goes_imagery() {
    global $api_urls;
    
    return api_request($api_urls['goes_imagery']);
}

/**
 * Formata a data para exibição no formato brasileiro
 * 
 * @param string $date Data no formato ISO
 * @return string Data formatada
 */
function format_date($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y H:i:s', $timestamp);
}
?>
