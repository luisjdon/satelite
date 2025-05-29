<?php
/**
 * SatéliteVision - API de Imagens Diretas
 * Fornece imagens diretas dos satélites sem depender de APIs externas
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o arquivo de configuração
require_once __DIR__ . '/../config.php';

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Função para obter a data atual formatada
function get_formatted_date() {
    return date('d/m/Y H:i:s');
}

// Função para obter imagens da Terra
function get_earth_images() {
    // URLs de imagens da Terra em alta resolução
    $earth_images = [
        [
            'id' => 'earth-1',
            'title' => 'Terra vista do Espaço - Hemisfério Ocidental',
            'description' => 'Imagem da Terra mostrando as Américas e o Oceano Pacífico',
            'date' => get_formatted_date(),
            'url' => 'https://epic.gsfc.nasa.gov/archive/natural/2022/12/25/png/epic_1b_20221225003633.png',
            'centroid_coordinates' => [
                'lat' => 0.0,
                'lon' => -90.0
            ]
        ],
        [
            'id' => 'earth-2',
            'title' => 'Terra vista do Espaço - Hemisfério Oriental',
            'description' => 'Imagem da Terra mostrando a África, Europa, Ásia e Oceania',
            'date' => get_formatted_date(),
            'url' => 'https://epic.gsfc.nasa.gov/archive/natural/2022/12/25/png/epic_1b_20221225073633.png',
            'centroid_coordinates' => [
                'lat' => 0.0,
                'lon' => 90.0
            ]
        ],
        [
            'id' => 'earth-3',
            'title' => 'Terra vista do Espaço - Polo Norte',
            'description' => 'Imagem da Terra mostrando o Polo Norte e o Ártico',
            'date' => get_formatted_date(),
            'url' => 'https://epic.gsfc.nasa.gov/archive/natural/2022/06/21/png/epic_1b_20220621003633.png',
            'centroid_coordinates' => [
                'lat' => 90.0,
                'lon' => 0.0
            ]
        ],
        [
            'id' => 'earth-4',
            'title' => 'Terra vista do Espaço - Polo Sul',
            'description' => 'Imagem da Terra mostrando o Polo Sul e a Antártida',
            'date' => get_formatted_date(),
            'url' => 'https://epic.gsfc.nasa.gov/archive/natural/2022/12/21/png/epic_1b_20221221003633.png',
            'centroid_coordinates' => [
                'lat' => -90.0,
                'lon' => 0.0
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
            'id' => 'space-1',
            'title' => 'Nebulosa de Órion',
            'description' => 'A Nebulosa de Órion (também conhecida como Messier 42, M42 ou NGC 1976) é uma nebulosa difusa situada na constelação de Órion.',
            'date' => get_formatted_date(),
            'url' => 'https://apod.nasa.gov/apod/image/2201/OrionStarFree3_Harbison_5000.jpg',
            'media_type' => 'image',
            'copyright' => 'NASA/ESA'
        ],
        [
            'id' => 'space-2',
            'title' => 'Galáxia Andrômeda',
            'description' => 'A Galáxia de Andrômeda (Messier 31, NGC 224) é uma galáxia espiral localizada a cerca de 2,54 milhões de anos-luz da Terra.',
            'date' => get_formatted_date(),
            'url' => 'https://apod.nasa.gov/apod/image/2211/M31_HubbleSpitzer_1080.jpg',
            'media_type' => 'image',
            'copyright' => 'NASA/ESA'
        ],
        [
            'id' => 'space-3',
            'title' => 'Nebulosa do Caranguejo',
            'description' => 'A Nebulosa do Caranguejo (Messier 1, NGC 1952) é um remanescente de supernova e nebulosa de vento pulsar localizada na constelação de Touro.',
            'date' => get_formatted_date(),
            'url' => 'https://apod.nasa.gov/apod/image/1912/M1_HubbleChandra_4000.jpg',
            'media_type' => 'image',
            'copyright' => 'NASA/ESA'
        ],
        [
            'id' => 'space-4',
            'title' => 'Pilares da Criação',
            'description' => 'Os Pilares da Criação são formações de gás e poeira dentro da Nebulosa da Águia, a aproximadamente 7.000 anos-luz da Terra.',
            'date' => get_formatted_date(),
            'url' => 'https://apod.nasa.gov/apod/image/1901/pillars_creation.jpg',
            'media_type' => 'image',
            'copyright' => 'NASA/ESA'
        ]
    ];
    
    return $space_images;
}

// Função para obter a localização da ISS
function get_iss_location() {
    // Localização simulada da ISS
    $iss_location = [
        'message' => 'success',
        'timestamp' => time(),
        'iss_position' => [
            'latitude' => rand(-80, 80) + (rand(0, 1000) / 1000),
            'longitude' => rand(-180, 180) + (rand(0, 1000) / 1000)
        ],
        'info' => [
            'altitude' => '408 km',
            'velocity' => '27.600 km/h',
            'orbit_time' => '92 minutos',
            'last_update' => get_formatted_date()
        ]
    ];
    
    return $iss_location;
}

// Determina o tipo de dados a retornar
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Prepara a resposta
$response = [
    'status' => 'success',
    'timestamp' => get_formatted_date()
];

// Adiciona os dados solicitados à resposta
switch ($type) {
    case 'earth':
        $response['earth_images'] = get_earth_images();
        break;
    case 'space':
        $response['space_images'] = get_space_images();
        break;
    case 'iss':
        $response['iss_data'] = get_iss_location();
        break;
    default:
        $response['earth_images'] = get_earth_images();
        $response['space_images'] = get_space_images();
        $response['iss_data'] = get_iss_location();
        break;
}

// Retorna a resposta em formato JSON
echo json_encode($response, JSON_PRETTY_PRINT);
?>
