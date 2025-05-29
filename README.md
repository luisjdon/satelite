# 🌎 SatéliteVision - Observação da Terra e do Espaço em Tempo Real

Um portal visual em PHP para observação da Terra e do espaço, com vídeos e imagens atualizados em tempo real. Este projeto permite aos usuários explorar imagens da Terra, do espaço profundo e acompanhar a localização da Estação Espacial Internacional (ISS).

![SatéliteVision](img/logo.png)

## Funcionalidades:

- ✅ **Vídeo ao vivo da Terra**: Duas câmeras HD da ISS (YouTube oficial NASA)
- ✅ **Última imagem da Terra via satélite**: Imagens da Terra capturadas pelo satélite DSCOVR EPIC da NASA
- ✅ **Galeria de imagens do Hubble e James Webb**: Explore imagens impressionantes do universo
- ✅ **Rastreamento ao vivo da ISS**: Mapa interativo com vídeo da Estação Espacial Internacional
- ✅ **Telescópios Públicos**: Acesso a imagens recentes do Las Cumbres Observatory (LCO)
- ✅ **Biblioteca NASA**: Pesquisa na NASA Image and Video Library com filtros interativos
- ✅ **Imagem Astronômica do Dia**: Veja a imagem astronômica do dia selecionada pela NASA
- ✅ **Código 100% em PHP**: Com chamadas AJAX e sistema de fallback para garantir visualização

## Estrutura do Projeto

- `/` - Arquivos principais do site
- `/api` - APIs e endpoints para obtenção de dados
- `/css` - Arquivos de estilo CSS
- `/js` - Scripts JavaScript
- `/img` - Imagens estáticas e de fallback
- `/includes` - Arquivos de inclusão e funções
- `/cache` - Diretório para cache de imagens e dados

## Páginas Principais

- `index.php` - Página inicial com visão geral do sistema
- `terra.php` - Visualização de imagens da Terra
- `espaco.php` - Visualização de imagens do espaço
- `iss.php` - Rastreamento da Estação Espacial Internacional
- `espaco_ao_vivo.php` - Visualizador avançado de imagens do espaço
- `verificar_imagens.php` - Ferramenta de diagnóstico de imagens
- `corrigir_imagens.php` - Ferramenta para correção automática de problemas
- `sobre.php` - Informações sobre o projeto

## APIs Utilizadas

- **NASA EPIC API** - Imagens da Terra em tempo real
- **NASA APOD API** - Imagem Astronômica do Dia
- **ISS Tracker API** - Localização da Estação Espacial Internacional
- **Hubble Site API** - Imagens do Telescópio Hubble
- **Webb Space Telescope API** - Imagens do Telescópio James Webb

## Instalação

1. Faça upload de todos os arquivos para seu servidor web com suporte a PHP
2. Verifique se os diretórios `/cache` e `/img` têm permissões de escrita (chmod 755)
3. Configure as chaves de API no arquivo `config.php` (opcional, o sistema funciona com a chave demo)
4. Acesse o site através do navegador
5. Em caso de problemas, acesse `verificar_imagens.php` para diagnóstico ou `corrigir_imagens.php` para correção automática

## Requisitos

- PHP 7.4 ou superior (recomendado PHP 8.0+)
- Extensões PHP: cURL, GD, JSON
- Conexão com internet para acessar as APIs externas
- Navegador moderno com suporte a JavaScript e CSS3

## Solução de Problemas

O SatéliteVision inclui ferramentas de diagnóstico e correção automática para resolver problemas comuns:

### Ferramentas de Diagnóstico

- **verificar_imagens.php**: Realiza um diagnóstico completo do sistema de imagens, verificando:
  - Conectividade com APIs
  - Disponibilidade de imagens
  - Permissões de diretórios
  - Cache de dados

- **corrigir_imagens.php**: Corrige automaticamente problemas comuns:
  - Cria diretórios necessários
  - Gera imagens de fallback
  - Corrige permissões
  - Atualiza arquivos JavaScript

### Problemas Comuns

1. **Imagens não carregam**
   - Acesse `verificar_imagens.php` para diagnóstico
   - Execute `corrigir_imagens.php` para correção automática
   - Verifique se o servidor tem acesso à internet

2. **Erros de CORS**
   - O sistema usa um proxy interno para contornar esses problemas
   - Verifique se `api/proxy.php` está funcionando corretamente

3. **Mapa da ISS não aparece**
   - Verifique se o Leaflet está sendo carregado corretamente
   - Verifique se `js/iss_map.js` está incluído na página

## Recursos Especiais

### Visualizador de Imagens do Espaço (`espaco_ao_vivo.php`)

Esta página especial foi desenvolvida para garantir a visualização de imagens do espaço mesmo quando outras partes do sistema possam apresentar problemas. Ela possui:

- **Imagens do Telescópio Hubble**: Nebulosa do Véu, Galáxia do Redemoinho, Pilares da Criação e mais
- **Imagens do Telescópio James Webb**: Nebulosa de Carina, Quinteto de Stephan, Campo Profundo SMACS 0723
- **Visualização em tela cheia**: Clique em qualquer imagem para ver em tela cheia com detalhes
- **Design responsivo**: Funciona perfeitamente em dispositivos móveis e desktops

### Sistema de Fallback Robusto

O SatéliteVision implementa um sistema de fallback em camadas para garantir que os usuários sempre vejam conteúdo, mesmo quando as APIs externas falham:

1. **API de imagens diretas**: Fornece URLs de imagens confiáveis e sempre disponíveis
2. **Cache de dados**: Armazena dados obtidos das APIs para reduzir requisições
3. **Imagens estáticas**: Usa imagens estáticas quando as APIs não respondem
4. **Atributo onerror**: Configura imagens para usar fallbacks quando não carregam

## Documentação Completa

Para uma documentação mais detalhada do projeto, consulte o arquivo `documentacao.md`.

## Créditos

- **Imagens**: NASA, ESA/Hubble, STScI (James Webb Space Telescope)
- **APIs**: NASA EPIC, NASA APOD, ISS Tracker
- **Bibliotecas**: Leaflet (mapas), Three.js (animações)

---

Desenvolvido como parte do projeto SatéliteVision para visualização de imagens espaciais em tempo real.
