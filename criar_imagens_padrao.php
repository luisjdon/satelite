<?php
/**
 * SatéliteVision - Criador de Imagens Padrão
 * Cria imagens padrão para uso quando as APIs não respondem
 */

// Habilita exibição de erros para diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o GD está habilitado
if (!function_exists('imagecreatetruecolor')) {
    die('A extensão GD não está habilitada. Por favor, habilite-a no PHP.');
}

// Função para criar uma imagem padrão
function criar_imagem_padrao($caminho, $titulo, $largura = 800, $altura = 600, $cor_fundo = [0, 0, 0], $cor_texto = [255, 255, 255]) {
    echo "<h3>Criando imagem: $caminho</h3>";
    
    // Cria o diretório se não existir
    $diretorio = dirname($caminho);
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
        echo "<p>Diretório criado: $diretorio</p>";
    }
    
    // Cria a imagem
    $imagem = imagecreatetruecolor($largura, $altura);
    
    // Define as cores
    $cor_fundo = imagecolorallocate($imagem, $cor_fundo[0], $cor_fundo[1], $cor_fundo[2]);
    $cor_texto = imagecolorallocate($imagem, $cor_texto[0], $cor_texto[1], $cor_texto[2]);
    $cor_borda = imagecolorallocate($imagem, 0, 100, 255);
    
    // Preenche o fundo
    imagefilledrectangle($imagem, 0, 0, $largura, $altura, $cor_fundo);
    
    // Adiciona uma borda
    imagerectangle($imagem, 10, 10, $largura - 10, $altura - 10, $cor_borda);
    
    // Adiciona o título
    $fonte = 5; // Tamanho da fonte (1-5)
    $largura_texto = imagefontwidth($fonte) * strlen($titulo);
    $x = ($largura - $largura_texto) / 2;
    $y = $altura / 2 - 20;
    imagestring($imagem, $fonte, $x, $y, $titulo, $cor_texto);
    
    // Adiciona texto adicional
    $texto_adicional = "Imagem padrão - SatéliteVision";
    $largura_texto_adicional = imagefontwidth(3) * strlen($texto_adicional);
    $x = ($largura - $largura_texto_adicional) / 2;
    $y = $altura / 2 + 20;
    imagestring($imagem, 3, $x, $y, $texto_adicional, $cor_texto);
    
    // Adiciona a data atual
    $data = date('d/m/Y H:i:s');
    $largura_data = imagefontwidth(2) * strlen($data);
    $x = ($largura - $largura_data) / 2;
    $y = $altura - 30;
    imagestring($imagem, 2, $x, $y, $data, $cor_texto);
    
    // Salva a imagem
    $extensao = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
    
    if ($extensao == 'jpg' || $extensao == 'jpeg') {
        $resultado = imagejpeg($imagem, $caminho, 90); // Qualidade 90%
    } elseif ($extensao == 'png') {
        $resultado = imagepng($imagem, $caminho, 9); // Compressão máxima
    } else {
        $resultado = imagejpeg($imagem, $caminho, 90); // Padrão: JPG
    }
    
    // Libera a memória
    imagedestroy($imagem);
    
    if ($resultado) {
        echo "<p style='color: green;'>Imagem criada com sucesso!</p>";
        echo "<img src='$caminho' style='max-width: 400px; border: 1px solid #ccc;' />";
        return true;
    } else {
        echo "<p style='color: red;'>Falha ao criar a imagem!</p>";
        return false;
    }
}

// Lista de imagens padrão a serem criadas
$imagens = [
    // Imagens da Terra
    ['caminho' => 'img/earth/earth-default.jpg', 'titulo' => 'Planeta Terra - Imagem Padrão', 'cor_fundo' => [0, 20, 60]],
    ['caminho' => 'img/earth/earth-south-america.jpg', 'titulo' => 'Terra - América do Sul', 'cor_fundo' => [0, 30, 70]],
    ['caminho' => 'img/earth/earth-north-america.jpg', 'titulo' => 'Terra - América do Norte', 'cor_fundo' => [0, 40, 80]],
    ['caminho' => 'img/earth/earth-africa-europe.jpg', 'titulo' => 'Terra - África e Europa', 'cor_fundo' => [0, 50, 90]],
    ['caminho' => 'img/earth/earth-asia-oceania.jpg', 'titulo' => 'Terra - Ásia e Oceania', 'cor_fundo' => [0, 60, 100]],
    
    // Imagens do Espaço
    ['caminho' => 'img/space/space-default.jpg', 'titulo' => 'Espaço - Imagem Padrão', 'cor_fundo' => [0, 0, 30]],
    ['caminho' => 'img/space/hubble.jpg', 'titulo' => 'Telescópio Espacial Hubble', 'cor_fundo' => [10, 0, 40]],
    ['caminho' => 'img/space/james-webb.jpg', 'titulo' => 'Telescópio Espacial James Webb', 'cor_fundo' => [20, 0, 50]],
    
    // Imagens da ISS
    ['caminho' => 'img/iss/iss-default.jpg', 'titulo' => 'Estação Espacial Internacional', 'cor_fundo' => [30, 0, 60]],
    ['caminho' => 'img/iss/iss.jpg', 'titulo' => 'ISS em Órbita', 'cor_fundo' => [40, 0, 70]],
    
    // Imagens do GOES
    ['caminho' => 'img/goes/goes-default.jpg', 'titulo' => 'Satélite GOES - Imagem Padrão', 'cor_fundo' => [50, 0, 80]],
    ['caminho' => 'img/goes/goes-full_disk.jpg', 'titulo' => 'GOES - Disco Completo', 'cor_fundo' => [60, 0, 90]],
    ['caminho' => 'img/goes/goes-conus.jpg', 'titulo' => 'GOES - América do Norte', 'cor_fundo' => [70, 0, 100]],
    
    // Imagens do Landsat
    ['caminho' => 'img/landsat/landsat-sample1.jpg', 'titulo' => 'Landsat - Amostra 1', 'cor_fundo' => [0, 40, 40]],
    ['caminho' => 'img/landsat/landsat-sample2.jpg', 'titulo' => 'Landsat - Amostra 2', 'cor_fundo' => [0, 50, 50]]
];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criador de Imagens Padrão - SatéliteVision</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2, h3 {
            color: #333;
        }
        .section {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        img {
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Criador de Imagens Padrão - SatéliteVision</h1>
    
    <div class="section">
        <h2>Criando Imagens Padrão</h2>
        <p>Este script cria imagens padrão para serem usadas quando as APIs não respondem.</p>
        
        <?php
        // Cria as imagens
        foreach ($imagens as $imagem) {
            criar_imagem_padrao(
                $imagem['caminho'],
                $imagem['titulo'],
                $imagem['largura'] ?? 800,
                $imagem['altura'] ?? 600,
                $imagem['cor_fundo'] ?? [0, 0, 0],
                $imagem['cor_texto'] ?? [255, 255, 255]
            );
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Conclusão</h2>
        <p>Todas as imagens padrão foram criadas com sucesso!</p>
        <p><a href="index.php">Voltar para a página inicial</a></p>
    </div>
</body>
</html>
