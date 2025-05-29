<?php
/**
 * SatéliteVision - API GOES
 * Obtém imagens do satélite GOES
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Parâmetros da requisição
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$type = isset($_GET['type']) ? $_GET['type'] : 'geocolor';
$region = isset($_GET['region']) ? $_GET['region'] : 'full_disk';

// Formata a data
$formatted_date = date('Ymd', strtotime($date));

// URLs base para diferentes satélites GOES
$goes_urls = [
    'goes16' => 'https://cdn.star.nesdis.noaa.gov/GOES16/ABI',
    'goes17' => 'https://cdn.star.nesdis.noaa.gov/GOES17/ABI',
    'goes18' => 'https://cdn.star.nesdis.noaa.gov/GOES18/ABI'
];

// Mapeamento de tipos de imagem
$type_mapping = [
    'geocolor' => 'GEOCOLOR',
    'band13' => 'BAND13',
    'band02' => 'BAND02',
    'band14' => 'BAND14'
];

// Mapeamento de regiões
$region_mapping = [
    'full_disk' => 'FD',
    'conus' => 'CONUS',
    'mesoscale_01' => 'MESO1',
    'mesoscale_02' => 'MESO2'
];

// Verifica se os parâmetros são válidos
if (!isset($type_mapping[$type])) {
    echo json_encode([
        'success' => false,
        'message' => 'Tipo de imagem inválido.',
        'fallback' => true
    ]);
    exit;
}

if (!isset($region_mapping[$region])) {
    echo json_encode([
        'success' => false,
        'message' => 'Região inválida.',
        'fallback' => true
    ]);
    exit;
}

// Constrói a URL da imagem
$satellite = 'goes16'; // Usamos GOES-16 como padrão
$url = $goes_urls[$satellite] . '/' . $region_mapping[$region] . '/' . $type_mapping[$type] . '/latest.jpg';

// Tenta obter a imagem
try {
    // Verifica se a URL existe
    $headers = get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo json_encode([
            'success' => true,
            'url' => $url,
            'timestamp' => date('d/m/Y H:i:s'),
            'satellite' => strtoupper($satellite),
            'type' => $type_mapping[$type],
            'region' => $region_mapping[$region]
        ]);
    } else {
        // Tenta uma URL alternativa
        $url = $goes_urls[$satellite] . '/' . $region_mapping[$region] . '/' . $type_mapping[$type] . '/1808x1808.jpg';
        $headers = get_headers($url);
        
        if ($headers && strpos($headers[0], '200') !== false) {
            echo json_encode([
                'success' => true,
                'url' => $url,
                'timestamp' => date('d/m/Y H:i:s'),
                'satellite' => strtoupper($satellite),
                'type' => $type_mapping[$type],
                'region' => $region_mapping[$region]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Imagem não encontrada para os parâmetros especificados.',
                'fallback' => true
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao acessar a imagem do satélite GOES: ' . $e->getMessage(),
        'fallback' => true
    ]);
}
?>
