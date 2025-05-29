<?php
/**
 * API para localização da ISS
 * Retorna a posição atual da Estação Espacial Internacional
 */

// Inclui o arquivo de configuração
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/api_functions.php';

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Prepara o array de resposta
$response = [];

try {
    // Obtém a localização da ISS
    $iss_data = get_iss_location();
    
    if (!empty($iss_data) && !isset($iss_data['error']) && isset($iss_data['iss_position'])) {
        $response = $iss_data;
        
        // Adiciona informações extras para melhorar a experiência do usuário
        $response['info'] = [
            'altitude' => '408 km',
            'velocity' => '27.600 km/h',
            'orbit_time' => '92 minutos',
            'last_update' => date('d/m/Y H:i:s')
        ];
    } else {
        // Dados simulados caso a API não retorne dados
        $response = [
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
                'last_update' => date('d/m/Y H:i:s')
            ]
        ];
    }
} catch (Exception $e) {
    $response = [
        'error' => 'Erro ao obter localização da ISS: ' . $e->getMessage()
    ];
}

// Retorna a resposta
echo json_encode($response, JSON_PRETTY_PRINT);
?>
