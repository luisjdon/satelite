<?php
/**
 * Arquivo de configuração do SatéliteVision
 * Contém chaves de API e outras configurações importantes
 */

// Configurações gerais
$config = [
    'site_name' => 'SatéliteVision',
    'site_description' => 'Visualização de imagens de satélites em tempo real',
    'site_url' => 'https://seu-dominio.com',
    'timezone' => 'America/Sao_Paulo',
    'debug_mode' => false,
];

// Chaves de API (substitua pelos valores reais quando for fazer o upload)
$api_keys = [
    'nasa' => 'API',  // Chave obtida em https://api.nasa.gov/
    'eonet' => 'API', // Mesma chave NASA para EONET
    'goes' => 'API',   // Mesma chave NASA para GOES
];

// URLs das APIs
$api_urls = [
    'nasa_epic' => 'https://api.nasa.gov/EPIC/api/natural',
    'nasa_imagery' => 'https://api.nasa.gov/planetary/apod',
    'iss_tracker' => 'http://api.open-notify.org/iss-now.json',
    'eonet' => 'https://eonet.gsfc.nasa.gov/api/v3/events',
    'goes_imagery' => 'https://services.swpc.noaa.gov/json/goes/primary/xrays-7-day.json',
];

// Configurações de cache
$cache = [
    'enabled' => true,
    'duration' => 300, // 5 minutos em segundos
    'directory' => __DIR__ . '/cache',
];

// Não modificar esta linha
defined('SATELITE_CONFIG_LOADED') or define('SATELITE_CONFIG_LOADED', true);
?>
