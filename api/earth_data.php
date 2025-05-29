<?php
/**
 * API para dados da Terra
 * Retorna imagens e eventos da Terra
 */

// Inclui o arquivo de configuração
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/api_functions.php';

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Verifica se o cache está habilitado
$cache_file = __DIR__ . '/../cache/earth_data.json';
$cache_valid = false;

if ($cache['enabled'] && file_exists($cache_file)) {
    $file_time = filemtime($cache_file);
    if (time() - $file_time < $cache['duration']) {
        echo file_get_contents($cache_file);
        exit;
    }
}

// Prepara o array de resposta
$response = [
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s'),
    'images' => [],
    'events' => []
];

try {
    // Obtém imagens da Terra do EPIC
    $epic_images = get_nasa_epic_images();
    
    if (!empty($epic_images) && !isset($epic_images['error'])) {
        foreach ($epic_images as $image) {
            // Construir a URL da imagem EPIC diretamente
            $date_parts = explode(' ', $image['date']);
            $date = $date_parts[0]; // Pega apenas a parte da data (YYYY-MM-DD)
            $parts = explode('-', $date);
            
            if (count($parts) === 3) {
                $year = $parts[0];
                $month = $parts[1];
                $day = $parts[2];
                $image_name = $image['image'];
                $image_url = "https://epic.gsfc.nasa.gov/archive/natural/{$year}/{$month}/{$day}/png/{$image_name}.png";
            } else {
                $image_url = 'img/earth/earth-default.jpg';
            }
            
            $response['images'][] = [
                'id' => $image['identifier'] ?? '',
                'title' => 'Terra vista do espaço',
                'description' => 'Imagem capturada pelo satélite DSCOVR usando a câmera EPIC',
                'date' => format_date($image['date'] ?? ''),
                'url' => $image_url,
                'centroid_coordinates' => [
                    'lat' => $image['centroid_coordinates']['lat'] ?? 0,
                    'lon' => $image['centroid_coordinates']['lon'] ?? 0
                ]
            ];
        }
    }
    
    // Obtém eventos naturais na Terra
    $earth_events = get_earth_events(10);
    
    if (!empty($earth_events) && !isset($earth_events['error']) && isset($earth_events['events'])) {
        foreach ($earth_events['events'] as $event) {
            $event_type = '';
            $event_location = '';
            
            // Determina o tipo de evento
            if (!empty($event['categories'])) {
                $event_type = $event['categories'][0]['title'] ?? '';
            }
            
            // Determina a localização do evento
            if (!empty($event['geometry'])) {
                $coordinates = $event['geometry'][0]['coordinates'] ?? [];
                if (!empty($coordinates)) {
                    $event_location = "Lat: {$coordinates[1]}, Lon: {$coordinates[0]}";
                }
            }
            
            $response['events'][] = [
                'id' => $event['id'] ?? '',
                'title' => $event['title'] ?? '',
                'description' => $event['description'] ?? '',
                'date' => isset($event['geometry'][0]['date']) ? format_date($event['geometry'][0]['date']) : '',
                'type' => strtolower(str_replace(' ', '', $event_type)),
                'location' => $event_location
            ];
        }
    }
    
    // Adiciona algumas imagens estáticas caso a API não retorne dados suficientes
    if (count($response['images']) < 3) {
        $static_images = [
            [
                'id' => 'static-1',
                'title' => 'Vista da Terra - América do Sul',
                'description' => 'Imagem da Terra mostrando a América do Sul e parte do Oceano Atlântico',
                'date' => date('d/m/Y'),
                'url' => 'img/earth/earth-south-america.jpg',
                'centroid_coordinates' => [
                    'lat' => -15.77972,
                    'lon' => -47.92972
                ]
            ],
            [
                'id' => 'static-2',
                'title' => 'Vista da Terra - América do Norte',
                'description' => 'Imagem da Terra mostrando a América do Norte e parte do Oceano Pacífico',
                'date' => date('d/m/Y'),
                'url' => 'img/earth/earth-north-america.jpg',
                'centroid_coordinates' => [
                    'lat' => 37.09024,
                    'lon' => -95.71289
                ]
            ],
            [
                'id' => 'static-3',
                'title' => 'Vista da Terra - África e Europa',
                'description' => 'Imagem da Terra mostrando o continente africano e parte da Europa',
                'date' => date('d/m/Y'),
                'url' => 'img/earth/earth-africa-europe.jpg',
                'centroid_coordinates' => [
                    'lat' => 9.14550,
                    'lon' => 18.28650
                ]
            ],
            [
                'id' => 'static-4',
                'title' => 'Vista da Terra - Ásia e Oceania',
                'description' => 'Imagem da Terra mostrando a Ásia e parte da Oceania',
                'date' => date('d/m/Y'),
                'url' => 'img/earth/earth-asia-oceania.jpg',
                'centroid_coordinates' => [
                    'lat' => 34.04700,
                    'lon' => 100.61950
                ]
            ]
        ];
        
        foreach ($static_images as $image) {
            $response['images'][] = $image;
        }
    }
    
    // Adiciona alguns eventos estáticos caso a API não retorne dados suficientes
    if (count($response['events']) < 3) {
        $static_events = [
            [
                'id' => 'static-event-1',
                'title' => 'Incêndio Florestal na Amazônia',
                'description' => 'Grande incêndio florestal detectado na região amazônica',
                'date' => date('d/m/Y'),
                'type' => 'wildfire',
                'location' => 'Lat: -3.4653, Lon: -62.2159'
            ],
            [
                'id' => 'static-event-2',
                'title' => 'Erupção Vulcânica no Havaí',
                'description' => 'Erupção do vulcão Kilauea com fluxo de lava',
                'date' => date('d/m/Y'),
                'type' => 'volcano',
                'location' => 'Lat: 19.4069, Lon: -155.2834'
            ],
            [
                'id' => 'static-event-3',
                'title' => 'Tempestade Tropical no Atlântico',
                'description' => 'Formação de tempestade tropical com ventos de até 100 km/h',
                'date' => date('d/m/Y'),
                'type' => 'storm',
                'location' => 'Lat: 25.7617, Lon: -80.1918'
            ]
        ];
        
        foreach ($static_events as $event) {
            $response['events'][] = $event;
        }
    }
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'Erro ao obter dados da Terra: ' . $e->getMessage()
    ];
}

// Converte o array para JSON
$json_response = json_encode($response, JSON_PRETTY_PRINT);

// Salva no cache se estiver habilitado
if ($cache['enabled']) {
    if (!is_dir($cache['directory'])) {
        mkdir($cache['directory'], 0755, true);
    }
    file_put_contents($cache_file, $json_response);
}

// Retorna a resposta
echo $json_response;
?>
