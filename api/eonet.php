<?php
/**
 * SatéliteVision - API EONET
 * Obtém eventos naturais da API EONET da NASA
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Chave da API da NASA
$api_key = "NRw74G5LCiPZfCSPqN0QzeBD0GFCxtRJ20R2mX4D";

// URL da API EONET da NASA
$url = "https://eonet.gsfc.nasa.gov/api/v3/events?api_key=$api_key&status=open&limit=10";

try {
    // Obtém os dados da API
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (!empty($data['events'])) {
        // Formata os eventos para uso no frontend
        $events = [];
        
        foreach ($data['events'] as $event) {
            // Determina o ícone com base na categoria
            $icon = 'fa-exclamation-triangle';
            $color = '#ff9800';
            
            switch ($event['categories'][0]['id']) {
                case 'wildfires':
                    $icon = 'fa-fire';
                    $color = '#ff5722';
                    break;
                case 'volcanoes':
                    $icon = 'fa-mountain';
                    $color = '#f44336';
                    break;
                case 'severeStorms':
                    $icon = 'fa-cloud-showers-heavy';
                    $color = '#2196f3';
                    break;
                case 'seaLakeIce':
                    $icon = 'fa-snowflake';
                    $color = '#90caf9';
                    break;
                case 'drought':
                    $icon = 'fa-sun';
                    $color = '#ff9800';
                    break;
                case 'dustHaze':
                    $icon = 'fa-smog';
                    $color = '#9e9e9e';
                    break;
                case 'earthquakes':
                    $icon = 'fa-house-damage';
                    $color = '#795548';
                    break;
                case 'floods':
                    $icon = 'fa-water';
                    $color = '#2962ff';
                    break;
                case 'landslides':
                    $icon = 'fa-mountain';
                    $color = '#8d6e63';
                    break;
                case 'manmade':
                    $icon = 'fa-industry';
                    $color = '#607d8b';
                    break;
                case 'seaLakeIce':
                    $icon = 'fa-icicles';
                    $color = '#b3e5fc';
                    break;
                case 'snow':
                    $icon = 'fa-snowflake';
                    $color = '#e1f5fe';
                    break;
                case 'tempExtremes':
                    $icon = 'fa-temperature-high';
                    $color = '#ff5722';
                    break;
                case 'waterColor':
                    $icon = 'fa-water';
                    $color = '#26a69a';
                    break;
            }
            
            // Obtém a data mais recente
            $date = isset($event['geometry'][0]['date']) ? $event['geometry'][0]['date'] : '';
            $formatted_date = !empty($date) ? date('d/m/Y', strtotime($date)) : '';
            
            // Obtém as coordenadas (para o mapa)
            $coordinates = [];
            if (isset($event['geometry'][0]['coordinates'])) {
                $coordinates = $event['geometry'][0]['coordinates'];
            }
            
            // Adiciona o evento formatado
            $events[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'description' => $event['description'] ?? '',
                'category' => $event['categories'][0]['title'],
                'date' => $formatted_date,
                'icon' => $icon,
                'color' => $color,
                'coordinates' => $coordinates,
                'sources' => isset($event['sources']) ? $event['sources'] : []
            ];
        }
        
        echo json_encode([
            'success' => true,
            'events' => $events,
            'total' => count($events)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Nenhum evento natural encontrado.',
            'fallback' => true
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao acessar a API EONET: ' . $e->getMessage(),
        'fallback' => true
    ]);
}
?>
