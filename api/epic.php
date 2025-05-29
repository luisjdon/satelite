<?php
/**
 * SatéliteVision - API EPIC
 * Obtém a última imagem da Terra do satélite DSCOVR EPIC da NASA
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Chave da API da NASA
$api_key = "NRw74G5LCiPZfCSPqN0QzeBD0GFCxtRJ20R2mX4D";

// Tenta obter os dados da API EPIC
try {
    $response = file_get_contents("https://api.nasa.gov/EPIC/api/natural?api_key=$api_key");
    $data = json_decode($response, true);

    if (!empty($data)) {
        $latest = $data[0];
        $img_date = explode(" ", $latest['date'])[0]; // ex: 2025-05-19
        $date_parts = explode("-", $img_date); // [2025, 05, 19]
        $img_name = $latest['image'];
        $img_url = "https://epic.gsfc.nasa.gov/archive/natural/" . $date_parts[0] . "/" . $date_parts[1] . "/" . $date_parts[2] . "/jpg/$img_name.jpg";

        echo json_encode([
            "url" => $img_url,
            "caption" => $latest['caption'],
            "date" => $latest['date'],
            "centroid_coordinates" => $latest['centroid_coordinates'] ?? null
        ]);
    } else {
        echo json_encode(["error" => "Falha ao obter dados da NASA EPIC."]);
    }
} catch (Exception $e) {
    echo json_encode([
        "error" => "Erro ao acessar a API EPIC: " . $e->getMessage(),
        "fallback_url" => "img/earth/earth-default.jpg"
    ]);
}
?>
