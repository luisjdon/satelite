<?php
/**
 * SatéliteVision - API LCO
 * Obtém imagens recentes do Las Cumbres Observatory (LCO)
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho como HTML
header('Content-Type: text/html; charset=utf-8');

// URL da API do Las Cumbres Observatory
$url = "https://observe.lco.global/api/images/?limit=6"; // últimos 6 registros

try {
    // Obtém os dados da API
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Verifica se há resultados
    if (!empty($data['results'])) {
        echo '<div class="lco-gallery">';
        foreach ($data['results'] as $img) {
            $thumb = $img['jpg_url'];
            $date = date('d/m/Y H:i', strtotime($img['created']));
            $title = $img['target_name'] ?? 'Objeto Celeste';
            $proposal = $img['proposal'] ?? 'Observação Astronômica';
            
            echo '<div class="lco-image-card">
                <div class="lco-image">
                    <img src="' . $thumb . '" alt="' . $title . '" loading="lazy" style="width:100%; max-width:600px; border-radius:10px;">
                </div>
                <div class="lco-info">
                    <h3>' . $title . '</h3>
                    <p><strong>Proposta:</strong> ' . $proposal . '</p>
                    <p><strong>Data:</strong> ' . $date . '</p>
                </div>
            </div>';
        }
        echo '</div>';
    } else {
        echo '<div class="error-message">
            <p>Nenhuma imagem encontrada no Las Cumbres Observatory.</p>
            <p>Tente novamente mais tarde ou verifique a conexão com a API.</p>
        </div>';
    }
} catch (Exception $e) {
    echo '<div class="error-message">
        <p>Erro ao acessar a API do Las Cumbres Observatory: ' . $e->getMessage() . '</p>
        <p>Tente novamente mais tarde ou verifique a conexão com a API.</p>
    </div>';
}
?>
