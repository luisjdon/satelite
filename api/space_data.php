<?php
/**
 * API para dados do espaço
 * Retorna imagens e informações do espaço
 */

// Inclui o arquivo de configuração
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/api_functions.php';

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Verifica se o cache está habilitado
$cache_file = __DIR__ . '/../cache/space_data.json';
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
    'apod' => null,
    'satellites' => []
];

try {
    // Obtém a imagem astronômica do dia
    $apod_data = get_nasa_apod();
    
    if (!empty($apod_data) && !isset($apod_data['error'])) {
        $response['apod'] = [
            'title' => $apod_data['title'] ?? 'Imagem Astronômica do Dia',
            'explanation' => $apod_data['explanation'] ?? 'Sem descrição disponível',
            'date' => format_date($apod_data['date'] ?? date('Y-m-d')),
            'url' => $apod_data['url'] ?? 'img/space-default.jpg',
            'media_type' => $apod_data['media_type'] ?? 'image',
            'copyright' => $apod_data['copyright'] ?? null
        ];
    } else {
        // Imagem padrão caso a API não retorne dados
        $response['apod'] = [
            'title' => 'Nebulosa de Órion',
            'explanation' => 'A Nebulosa de Órion (também conhecida como Messier 42, M42 ou NGC 1976) é uma nebulosa difusa situada na constelação de Órion. É uma das nebulosas mais brilhantes e pode ser vista a olho nu no céu noturno.',
            'date' => date('d/m/Y'),
            'url' => 'img/space/space-default.jpg',
            'media_type' => 'image',
            'copyright' => 'NASA/ESA'
        ];
    }
    
    // Adiciona informações sobre satélites
    $response['satellites'] = [
        [
            'id' => 'hubble',
            'name' => 'Telescópio Espacial Hubble',
            'description' => 'Lançado em 1990, o Hubble é um dos maiores e mais versáteis telescópios espaciais, fornecendo imagens de alta resolução do universo.',
            'launch_date' => '24/04/1990',
            'altitude' => '540 km',
            'image' => 'img/hubble.jpg',
            'agency' => 'NASA/ESA'
        ],
        [
            'id' => 'james-webb',
            'name' => 'Telescópio Espacial James Webb',
            'description' => 'O sucessor do Hubble, projetado para observar objetos muito distantes e fracos no universo, utilizando principalmente o infravermelho.',
            'launch_date' => '25/12/2021',
            'altitude' => '1,5 milhão km',
            'image' => 'img/james-webb.jpg',
            'agency' => 'NASA/ESA/CSA'
        ],
        [
            'id' => 'iss',
            'name' => 'Estação Espacial Internacional',
            'description' => 'Laboratório espacial em órbita terrestre baixa, habitado continuamente desde 2000, servindo como plataforma de pesquisa e colaboração internacional.',
            'launch_date' => '20/11/1998',
            'altitude' => '408 km',
            'image' => 'img/iss.jpg',
            'agency' => 'NASA/Roscosmos/ESA/JAXA/CSA'
        ],
        [
            'id' => 'goes',
            'name' => 'Satélite GOES',
            'description' => 'Satélites meteorológicos geoestacionários que fornecem imagens contínuas e monitoramento atmosférico da Terra.',
            'launch_date' => 'Vários',
            'altitude' => '35.786 km',
            'image' => 'img/goes.jpg',
            'agency' => 'NOAA/NASA'
        ]
    ];
    
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'Erro ao obter dados do espaço: ' . $e->getMessage()
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
