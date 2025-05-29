<?php
// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chave da API da NASA
$api_key = "NRw74G5LCiPZfCSPqN0QzeBD0GFCxtRJ20R2mX4D";

// URL da API EPIC da NASA
$url = "https://api.nasa.gov/EPIC/api/natural?api_key=$api_key";

echo "<h1>Teste de Conexão com API da NASA</h1>";
echo "<h2>Testando API EPIC (Imagens da Terra)</h2>";

// Testa usando cURL (mais robusto)
function fetch_nasa_data($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'data' => $data,
        'http_code' => $http_code,
        'error' => $error
    ];
}

// Testa a API EPIC
$result = fetch_nasa_data($url);

echo "<h3>Resultado da Requisição:</h3>";
echo "<p>Código HTTP: " . $result['http_code'] . "</p>";

if ($result['error']) {
    echo "<p>Erro cURL: " . $result['error'] . "</p>";
} else {
    echo "<p>Resposta recebida com sucesso!</p>";
    
    // Decodifica o JSON
    $data = json_decode($result['data'], true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Erro ao decodificar JSON: " . json_last_error_msg() . "</p>";
    } else {
        if (empty($data)) {
            echo "<p>Nenhum dado retornado pela API.</p>";
        } else {
            echo "<h3>Dados recebidos:</h3>";
            echo "<pre>" . print_r(array_slice($data, 0, 1), true) . "</pre>";
            
            // Tenta exibir a primeira imagem
            if (isset($data[0]['date']) && isset($data[0]['image'])) {
                $image_data = $data[0];
                $date_parts = explode(" ", $image_data['date']);
                $date = $date_parts[0]; // yyyy-mm-dd
                list($year, $month, $day) = explode("-", $date);
                
                $image_url = "https://epic.gsfc.nasa.gov/archive/natural/{$year}/{$month}/{$day}/png/{$image_data['image']}.png";
                
                echo "<h3>Imagem da Terra:</h3>";
                echo "<img src=\"$image_url\" style=\"max-width: 500px;\" />";
            }
        }
    }
}

// Teste da API ISS Location
echo "<h2>Testando API de Localização da ISS</h2>";
$iss_url = "http://api.open-notify.org/iss-now.json";
$iss_result = fetch_nasa_data($iss_url);

echo "<h3>Resultado da Requisição ISS:</h3>";
echo "<p>Código HTTP: " . $iss_result['http_code'] . "</p>";

if ($iss_result['error']) {
    echo "<p>Erro cURL: " . $iss_result['error'] . "</p>";
} else {
    echo "<p>Resposta recebida com sucesso!</p>";
    
    // Decodifica o JSON
    $iss_data = json_decode($iss_result['data'], true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Erro ao decodificar JSON: " . json_last_error_msg() . "</p>";
    } else {
        echo "<h3>Dados da ISS:</h3>";
        echo "<pre>" . print_r($iss_data, true) . "</pre>";
    }
}

// Teste da API APOD (Astronomy Picture of the Day)
echo "<h2>Testando API APOD (Imagem Astronômica do Dia)</h2>";
$apod_url = "https://api.nasa.gov/planetary/apod?api_key=$api_key";
$apod_result = fetch_nasa_data($apod_url);

echo "<h3>Resultado da Requisição APOD:</h3>";
echo "<p>Código HTTP: " . $apod_result['http_code'] . "</p>";

if ($apod_result['error']) {
    echo "<p>Erro cURL: " . $apod_result['error'] . "</p>";
} else {
    echo "<p>Resposta recebida com sucesso!</p>";
    
    // Decodifica o JSON
    $apod_data = json_decode($apod_result['data'], true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Erro ao decodificar JSON: " . json_last_error_msg() . "</p>";
    } else {
        echo "<h3>Dados do APOD:</h3>";
        echo "<pre>" . print_r($apod_data, true) . "</pre>";
        
        if (isset($apod_data['url'])) {
            echo "<h3>Imagem Astronômica do Dia:</h3>";
            echo "<img src=\"{$apod_data['url']}\" style=\"max-width: 500px;\" />";
        }
    }
}
?>
