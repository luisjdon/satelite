# Documentação do Projeto SatéliteVision

## Visão Geral

O SatéliteVision é uma plataforma web desenvolvida em PHP para visualização de imagens de satélites e telescópios espaciais em tempo real. O sistema permite aos usuários explorar imagens da Terra, do espaço e acompanhar a localização da Estação Espacial Internacional (ISS).

**Data da documentação:** 18/05/2025

## Estrutura do Projeto

```
satelite/
├── api/                    # APIs e endpoints para obtenção de dados
│   ├── earth_data.php      # API para dados e imagens da Terra
│   ├── space_data.php      # API para dados e imagens do espaço
│   ├── iss_location.php    # API para localização da ISS
│   ├── imagens_diretas.php # API robusta para imagens diretas (fallback)
│   └── proxy.php           # Proxy para contornar problemas de CORS
├── css/                    # Arquivos de estilo
│   └── style.css           # Estilos principais do site
├── img/                    # Imagens estáticas e de fallback
│   ├── earth/              # Imagens da Terra
│   ├── space/              # Imagens do espaço
│   └── iss/                # Imagens da ISS
├── includes/               # Arquivos de inclusão e funções
│   ├── api_functions.php   # Funções para comunicação com APIs externas
│   └── layout_functions.php # Funções para renderização do layout
├── js/                     # Scripts JavaScript
│   ├── main.js             # Script principal
│   ├── stars.js            # Script para animação de estrelas
│   └── iss_map.js          # Script para o mapa da ISS
├── cache/                  # Diretório para cache de imagens e dados
├── config.php              # Configurações do sistema
├── index.php               # Página inicial
├── terra.php               # Página de visualização da Terra
├── espaco.php              # Página de visualização do espaço
├── iss.php                 # Página de rastreamento da ISS
├── sobre.php               # Página sobre o projeto
├── espaco_ao_vivo.php      # Visualizador direto de imagens do espaço
├── verificar_imagens.php   # Ferramenta de diagnóstico de imagens
├── corrigir_imagens.php    # Ferramenta para correção de problemas
└── documentacao.md         # Este arquivo de documentação
```

## Requisitos do Sistema

- Servidor web com PHP 7.4+ (recomendado PHP 8.0+)
- Extensões PHP: cURL, GD, JSON
- Acesso à internet para comunicação com APIs externas
- Navegador moderno com suporte a JavaScript e CSS3

## Configuração

O arquivo `config.php` contém as configurações principais do sistema:

```php
define('SATELITE_CONFIG_LOADED', true);

// Configurações do site
$config = [
    'site_name' => 'SatéliteVision',
    'site_description' => 'Visualização de imagens de satélites em tempo real',
    'admin_email' => 'admin@satelitevision.com',
    
    // Configurações de cache
    'cache_enabled' => true,
    'cache_dir' => __DIR__ . '/cache',
    'cache_time' => 3600, // 1 hora
    
    // Chaves de API (quando necessárias)
    'api_keys' => [
        'nasa' => 'DEMO_KEY' // Chave demo da NASA
    ]
];
```

## Módulos Principais

### 1. Sistema de APIs

#### 1.1 API de Imagens Diretas (`api/imagens_diretas.php`)

Esta é a API principal e mais robusta do sistema, desenvolvida para garantir que as imagens sejam sempre exibidas, mesmo quando as APIs externas falham.

**Endpoints:**
- `?type=earth` - Retorna imagens da Terra
- `?type=space` - Retorna imagens do espaço
- `?type=iss` - Retorna dados da localização da ISS
- `?type=all` - Retorna todos os dados acima

**Exemplo de resposta:**
```json
{
    "status": "success",
    "timestamp": "18/05/2025 23:30:45",
    "earth_images": [
        {
            "id": "earth-1",
            "title": "Terra vista do Espaço - Hemisfério Ocidental",
            "description": "Imagem da Terra mostrando as Américas e o Oceano Pacífico",
            "date": "18/05/2025 23:30:45",
            "url": "https://epic.gsfc.nasa.gov/archive/natural/2022/12/25/png/epic_1b_20221225003633.png",
            "centroid_coordinates": {
                "lat": 0.0,
                "lon": -90.0
            }
        },
        // Mais imagens...
    ]
}
```

#### 1.2 API de Dados da Terra (`api/earth_data.php`)

Obtém imagens e dados da Terra de várias fontes, incluindo o satélite DSCOVR EPIC da NASA.

**Parâmetros:**
- `date` (opcional) - Data específica para buscar imagens (formato: YYYY-MM-DD)

#### 1.3 API de Dados do Espaço (`api/space_data.php`)

Obtém imagens e dados do espaço, incluindo a Imagem Astronômica do Dia (APOD) da NASA.

#### 1.4 API de Localização da ISS (`api/iss_location.php`)

Obtém a localização atual da Estação Espacial Internacional.

#### 1.5 Proxy (`api/proxy.php`)

Implementa um proxy para contornar problemas de CORS ao acessar APIs externas.

### 2. Sistema de Funções de API (`includes/api_functions.php`)

Este arquivo contém funções para comunicação com APIs externas, tratamento de erros e cache.

**Funções principais:**
- `api_request($url, $params = [], $method = 'GET')` - Realiza requisições a APIs externas
- `get_epic_image_url($image_data)` - Constrói URLs para imagens do EPIC
- `cache_data($key, $data, $expiry = 3600)` - Armazena dados em cache
- `get_cached_data($key)` - Recupera dados do cache
- `log_api_error($message, $data = [])` - Registra erros de API

### 3. Sistema de Layout (`includes/layout_functions.php`)

Contém funções para renderização do layout e componentes da interface.

**Funções principais:**
- `render_header($title = '', $description = '')` - Renderiza o cabeçalho HTML
- `render_footer()` - Renderiza o rodapé HTML
- `render_satellite_card($title, $image, $description, $link = '#')` - Renderiza um card de satélite
- `render_loader($message = 'Carregando...')` - Renderiza um indicador de carregamento
- `render_image_gallery($images)` - Renderiza uma galeria de imagens
- `render_interactive_map($id = 'map', $lat = 0, $lng = 0, $zoom = 2)` - Renderiza um mapa interativo
- `render_stats_section($stats)` - Renderiza uma seção de estatísticas

### 4. Scripts JavaScript

#### 4.1 Script Principal (`js/main.js`)

Gerencia a interatividade do site, incluindo chamadas AJAX para APIs e renderização de imagens.

**Funções principais:**
- `initSatelliteData()` - Inicializa os dados dos satélites
- `loadEarthData()` - Carrega dados da Terra
- `loadSpaceData()` - Carrega dados do espaço
- `loadISSLocation()` - Carrega a localização da ISS
- `renderEarthImages(container, images)` - Renderiza imagens da Terra
- `renderAPOD(container, apod)` - Renderiza a Imagem Astronômica do Dia
- `renderEarthEvents(container, events)` - Renderiza eventos naturais na Terra
- `updateISSInfo(lat, lng, info = {})` - Atualiza informações da ISS

#### 4.2 Mapa da ISS (`js/iss_map.js`)

Gerencia o mapa e a posição da ISS.

**Funções principais:**
- `initISSMap()` - Inicializa o mapa da ISS
- `updateISSPosition(lat, lng)` - Atualiza a posição da ISS no mapa
- `startISSTracking()` - Inicia o rastreamento da ISS

#### 4.3 Animação de Estrelas (`js/stars.js`)

Cria uma animação de fundo com estrelas usando Three.js.

### 5. Páginas Principais

#### 5.1 Página Inicial (`index.php`)

Apresenta uma visão geral do sistema com links para as principais seções.

#### 5.2 Página da Terra (`terra.php`)

Exibe imagens da Terra capturadas por satélites e informações sobre eventos naturais.

#### 5.3 Página do Espaço (`espaco.php`)

Exibe imagens do espaço, incluindo a Imagem Astronômica do Dia e imagens de telescópios.

#### 5.4 Página da ISS (`iss.php`)

Mostra a localização atual da ISS em um mapa interativo e fornece informações adicionais.

#### 5.5 Visualizador de Imagens do Espaço (`espaco_ao_vivo.php`)

Página especializada para visualização de imagens do espaço de várias fontes, incluindo o Telescópio Hubble e o James Webb.

**Características:**
- Visualização em tela cheia das imagens
- Organização por fonte (Hubble, James Webb, APOD)
- Interface responsiva e moderna
- Carregamento de imagens direto das fontes oficiais

### 6. Ferramentas de Diagnóstico e Manutenção

#### 6.1 Verificador de Imagens (`verificar_imagens.php`)

Ferramenta para diagnosticar problemas com imagens no sistema.

**Funcionalidades:**
- Verifica a conectividade com APIs
- Testa a disponibilidade de imagens
- Verifica permissões de diretórios
- Exibe relatórios detalhados

#### 6.2 Corretor de Imagens (`corrigir_imagens.php`)

Ferramenta para corrigir problemas com imagens automaticamente.

**Funcionalidades:**
- Cria diretórios necessários
- Gera imagens de fallback
- Corrige permissões
- Atualiza arquivos JavaScript

## Fluxo de Dados

1. O usuário acessa uma página do site
2. O JavaScript carrega e inicializa os componentes necessários
3. São feitas requisições às APIs internas (earth_data.php, space_data.php, iss_location.php)
4. As APIs internas verificam o cache e, se necessário, fazem requisições às APIs externas
5. Os dados são processados e retornados ao JavaScript
6. O JavaScript renderiza os dados na interface do usuário

Em caso de falha nas APIs externas, o sistema usa a API de imagens diretas (`imagens_diretas.php`) como fallback, garantindo que o usuário sempre veja conteúdo.

## Mecanismos de Fallback

O sistema implementa vários mecanismos de fallback para garantir a disponibilidade de imagens:

1. **Cache de dados** - Armazena dados obtidos das APIs para reduzir requisições e garantir disponibilidade offline
2. **Imagens estáticas** - Usa imagens estáticas quando as APIs não respondem
3. **API de imagens diretas** - Fornece URLs de imagens confiáveis e sempre disponíveis
4. **Atributo onerror** - Configura imagens para usar fallbacks quando não carregam
5. **Proxy** - Contorna problemas de CORS ao acessar APIs externas

## APIs Externas Utilizadas

1. **NASA EPIC API** - Imagens da Terra do satélite DSCOVR
   - URL: https://epic.gsfc.nasa.gov/api/natural
   - Documentação: https://epic.gsfc.nasa.gov/about/api

2. **NASA APOD API** - Imagem Astronômica do Dia
   - URL: https://api.nasa.gov/planetary/apod
   - Documentação: https://api.nasa.gov

3. **ISS Tracker API** - Localização da Estação Espacial Internacional
   - URL: http://api.open-notify.org/iss-now.json
   - Documentação: http://open-notify.org/Open-Notify-API/ISS-Location-Now/

4. **Hubble Site API** - Imagens do Telescópio Hubble
   - URL: https://esahubble.org/api/v0/
   - Documentação: https://esahubble.org/about/api/

5. **Webb Space Telescope API** - Imagens do Telescópio James Webb
   - URL: https://stsci-opo.org/api/
   - Documentação: Não disponível publicamente

## Solução de Problemas

### Problemas Comuns e Soluções

1. **Imagens não carregam**
   - Verifique a conectividade com as APIs externas
   - Execute `verificar_imagens.php` para diagnóstico
   - Execute `corrigir_imagens.php` para correção automática
   - Verifique se os diretórios de cache têm permissões de escrita

2. **Erros de CORS**
   - Use o proxy interno (`api/proxy.php`) para acessar APIs externas
   - Verifique se o servidor está configurado corretamente

3. **Mapa da ISS não carrega**
   - Verifique se o Leaflet está sendo carregado corretamente
   - Verifique se `iss_map.js` está incluído na página
   - Verifique se a API de localização da ISS está respondendo

4. **Erros de PHP**
   - Verifique se as extensões necessárias estão instaladas (cURL, GD, JSON)
   - Verifique os logs de erro do PHP para mais detalhes

### Ferramentas de Diagnóstico

1. **verificar_imagens.php** - Diagnóstico completo do sistema de imagens
2. **corrigir_imagens.php** - Correção automática de problemas
3. **teste_api_nasa.php** - Teste direto das APIs da NASA
4. **teste_epic_direto.html** - Teste da API EPIC diretamente via JavaScript
5. **teste_epic_proxy.html** - Teste da API EPIC usando o proxy

## Melhorias Futuras

1. **Autenticação de usuários** - Implementar sistema de login para personalização
2. **Mais fontes de imagens** - Adicionar mais satélites e telescópios
3. **Notificações em tempo real** - Alertas sobre eventos astronômicos
4. **Aplicativo móvel** - Versão para dispositivos móveis
5. **Modo offline** - Melhorar o suporte para uso offline
6. **Integração com redes sociais** - Compartilhamento de imagens
7. **Visualizador 3D** - Modelo 3D da Terra e do espaço
8. **Traduções** - Suporte para múltiplos idiomas

## Créditos e Licenças

- **Imagens da NASA** - Domínio público, cortesia da NASA
- **Imagens da ESA/Hubble** - Crédito: ESA/Hubble & NASA
- **Leaflet** - Licença BSD-2-Clause
- **Font Awesome** - Licença MIT (ícones)
- **Three.js** - Licença MIT (animação de estrelas)

## Contato e Suporte

Para suporte técnico ou dúvidas sobre o projeto, entre em contato:

- **Email**: admin@satelitevision.com
- **Site**: https://satelitevision.com
- **GitHub**: https://github.com/satelitevision

---

Documentação criada em 18/05/2025.
