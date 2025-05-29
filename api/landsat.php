<?php
/**
 * SatéliteVision - API Landsat
 * Obtém imagens do satélite Landsat 8
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Chave da API da NASA
$api_key = "NRw74G5LCiPZfCSPqN0QzeBD0GFCxtRJ20R2mX4D";

// Parâmetros da requisição
$location = isset($_GET['location']) ? $_GET['location'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d', strtotime('-30 days'));
$cloud_cover = isset($_GET['cloud_cover']) ? intval($_GET['cloud_cover']) : 20;

// Verifica se a localização foi fornecida
if (empty($location)) {
    echo json_encode([
        'success' => false,
        'message' => 'Localização não especificada.',
        'fallback' => true
    ]);
    exit;
}

// Função para obter coordenadas a partir do nome da localização
function get_coordinates($location) {
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($location) . "&format=json&limit=1";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'SatéliteVision/1.0');
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (!empty($data)) {
        return [
            'lat' => $data[0]['lat'],
            'lon' => $data[0]['lon'],
            'display_name' => $data[0]['display_name']
        ];
    }
    
    return null;
}

// Obtém as coordenadas da localização
$coordinates = get_coordinates($location);

if (!$coordinates) {
    echo json_encode([
        'success' => false,
        'message' => 'Não foi possível encontrar as coordenadas para a localização especificada.',
        'fallback' => true
    ]);
    exit;
}

// Formata as datas para a API da NASA
$start_date = date('Y-m-d', strtotime($date . ' -15 days'));
$end_date = date('Y-m-d', strtotime($date . ' +15 days'));

// URL da API Earth Observation da NASA
$url = "https://api.nasa.gov/planetary/earth/assets?lon={$coordinates['lon']}&lat={$coordinates['lat']}&date={$date}&dim=0.15&api_key={$api_key}";

try {
    // Obtém os dados da API
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (isset($data['url'])) {
        echo json_encode([
            'success' => true,
            'url' => $data['url'],
            'date' => $data['date'],
            'location' => [
                'name' => $coordinates['display_name'],
                'lat' => $coordinates['lat'],
                'lon' => $coordinates['lon']
            ],
            'id' => $data['id'] ?? '',
            'resource' => $data['resource'] ?? ''
        ]);
    } else {
        // Tenta usar uma API alternativa
        $url = "https://api.nasa.gov/planetary/earth/imagery?lon={$coordinates['lon']}&lat={$coordinates['lat']}&date={$date}&cloud_score=True&api_key={$api_key}";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        if (isset($data['url'])) {
            echo json_encode([
                'success' => true,
                'url' => $data['url'],
                'date' => $data['date'],
                'location' => [
                    'name' => $coordinates['display_name'],
                    'lat' => $coordinates['lat'],
                    'lon' => $coordinates['lon']
                ],
                'cloud_score' => $data['cloud_score'] ?? null,
                'id' => $data['id'] ?? ''
            ]);
        } else {
            // Se ainda não encontrou, usa imagens estáticas do Landsat
            $landsat_images = [
                'https://landsat.visibleearth.nasa.gov/view.php?id=148582',
                'https://www.nasa.gov/sites/default/files/styles/full_width/public/thumbnails/image/48000794302_e6d2e4d323_o.jpg',
                'https://eoimages.gsfc.nasa.gov/images/imagerecords/148000/148582/brazil_oli_2022157_lrg.jpg'
            ];
            
            echo json_encode([
                'success' => true,
                'url' => $landsat_images[array_rand($landsat_images)],
                'date' => date('Y-m-d'),
                'location' => [
                    'name' => $coordinates['display_name'],
                    'lat' => $coordinates['lat'],
                    'lon' => $coordinates['lon']
                ],
                'note' => 'Imagem estática do Landsat 8 (não foi possível encontrar imagem específica para esta localização e data)'
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao acessar a API da NASA: ' . $e->getMessage(),
        'fallback' => true
    ]);
}
?>
