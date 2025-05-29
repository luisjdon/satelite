<?php
/**
 * Proxy para APIs externas
 * Contorna problemas de CORS e facilita o acesso às APIs
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtém a URL da requisição
$url = $_GET['url'] ?? '';

// Verifica se a URL é válida
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'URL inválida']);
    exit;
}

// Inclui a chave da API se necessário
if (strpos($url, 'api.nasa.gov') !== false && strpos($url, 'api_key=') === false) {
    require_once __DIR__ . '/../config.php';
    $separator = (strpos($url, '?') === false) ? '?' : '&';
    $url .= $separator . 'api_key=' . $api_keys['nasa'];
}

// Inicializa o cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Executa a requisição
$data = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Verifica se houve erro
if ($error) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na requisição: ' . $error]);
    exit;
}

// Verifica se o código HTTP é válido
if ($httpCode >= 400) {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Erro na API externa: ' . $httpCode]);
    exit;
}

// Define o tipo de conteúdo da resposta
header("Content-Type: $contentType");

// Retorna os dados
echo $data;
?>
