<?php
/**
 * SatéliteVision - Página de Ferramentas
 * Lista todas as ferramentas de diagnóstico e teste
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferramentas - SatéliteVision</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        h1, h2 {
            color: #0066cc;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .tool-card {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .tool-card h3 {
            margin-top: 0;
            color: #0066cc;
        }
        .tool-card p {
            color: #666;
        }
        .btn {
            display: inline-block;
            background-color: #0066cc;
            color: #fff;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #0055aa;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #0066cc;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ferramentas de Diagnóstico e Teste - SatéliteVision</h1>
        
        <div class="card">
            <p>Esta página lista todas as ferramentas de diagnóstico e teste disponíveis para o SatéliteVision. Estas ferramentas ajudam a identificar e resolver problemas com as APIs, imagens e configurações.</p>
        </div>
        
        <h2>Ferramentas Disponíveis</h2>
        
        <div class="tools-grid">
            <div class="tool-card">
                <h3>Diagnóstico Completo</h3>
                <p>Realiza um diagnóstico completo do sistema, verificando APIs, diretórios, imagens e configurações.</p>
                <a href="diagnostico.php" class="btn">Executar</a>
            </div>
            
            <div class="tool-card">
                <h3>Teste de APIs da NASA</h3>
                <p>Testa a conexão com as APIs da NASA (EPIC, APOD) e da ISS usando PHP.</p>
                <a href="teste_api_nasa.php" class="btn">Executar</a>
            </div>
            
            <div class="tool-card">
                <h3>Teste EPIC Direto</h3>
                <p>Testa a API EPIC diretamente via JavaScript no navegador.</p>
                <a href="teste_epic_direto.html" class="btn">Executar</a>
            </div>
            
            <div class="tool-card">
                <h3>Teste EPIC via Proxy</h3>
                <p>Testa a API EPIC usando o proxy para evitar problemas de CORS.</p>
                <a href="teste_epic_proxy.html" class="btn">Executar</a>
            </div>
            
            <div class="tool-card">
                <h3>Criar Imagens Padrão</h3>
                <p>Cria imagens padrão para serem usadas quando as APIs não respondem.</p>
                <a href="criar_imagens_padrao.php" class="btn">Executar</a>
            </div>
            
            <div class="tool-card">
                <h3>Proxy para APIs</h3>
                <p>Proxy para acessar APIs externas e evitar problemas de CORS.</p>
                <a href="api/proxy.php?url=<?php echo urlencode('http://api.open-notify.org/iss-now.json'); ?>" class="btn">Testar</a>
            </div>
        </div>
        
        <h2>Endpoints da API</h2>
        
        <div class="tools-grid">
            <div class="tool-card">
                <h3>Earth Data API</h3>
                <p>Retorna dados e imagens da Terra.</p>
                <a href="api/earth_data.php" class="btn">Acessar</a>
            </div>
            
            <div class="tool-card">
                <h3>Space Data API</h3>
                <p>Retorna dados e imagens do espaço.</p>
                <a href="api/space_data.php" class="btn">Acessar</a>
            </div>
            
            <div class="tool-card">
                <h3>ISS Location API</h3>
                <p>Retorna a localização atual da ISS.</p>
                <a href="api/iss_location.php" class="btn">Acessar</a>
            </div>
        </div>
        
        <a href="index.php" class="back-link">← Voltar para a página inicial</a>
    </div>
</body>
</html>
