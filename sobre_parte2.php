<?php
/**
 * SatéliteVision - Página Sobre (Parte 2)
 * Tecnologias utilizadas e equipe
 */

// Esta parte deve ser incluída após sobre_parte1.php
?>

<!-- Seção Tecnologias -->
<section class="featured-section">
    <h2 class="section-title">Tecnologias Utilizadas</h2>
    <p class="section-description">O SatéliteVision foi desenvolvido utilizando tecnologias modernas para proporcionar uma experiência futurística e responsiva.</p>
    
    <div class="tech-grid">
        <div class="tech-item">
            <div class="tech-icon"><i class="fab fa-php"></i></div>
            <h3>PHP</h3>
            <p>Linguagem de programação server-side utilizada para processar requisições e comunicar-se com as APIs.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fab fa-js"></i></div>
            <h3>JavaScript</h3>
            <p>Utilizado para criar interatividade e animações dinâmicas na interface do usuário.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fab fa-css3-alt"></i></div>
            <h3>CSS3</h3>
            <p>Responsável pelo estilo futurístico e animações visuais do site.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fab fa-html5"></i></div>
            <h3>HTML5</h3>
            <p>Estrutura semântica para o conteúdo web com suporte a recursos modernos.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fas fa-map-marked-alt"></i></div>
            <h3>Leaflet</h3>
            <p>Biblioteca JavaScript para mapas interativos utilizados no rastreamento da ISS.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fas fa-cubes"></i></div>
            <h3>Three.js</h3>
            <p>Biblioteca para criação de gráficos 3D no navegador, utilizada para a animação de estrelas.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fas fa-database"></i></div>
            <h3>Cache System</h3>
            <p>Sistema de cache para otimizar requisições às APIs e melhorar o desempenho.</p>
        </div>
        
        <div class="tech-item">
            <div class="tech-icon"><i class="fas fa-mobile-alt"></i></div>
            <h3>Design Responsivo</h3>
            <p>Interface adaptável a diferentes tamanhos de tela e dispositivos.</p>
        </div>
    </div>
</section>

<!-- Seção FAQ -->
<section class="featured-section">
    <h2 class="section-title">Perguntas Frequentes</h2>
    
    <div class="faq-container">
        <div class="faq-item">
            <div class="faq-question">
                <h3>As imagens são realmente em tempo real?</h3>
                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>A maioria das imagens é atualizada regularmente, mas nem todas são estritamente em "tempo real". As imagens da Terra do EPIC são atualizadas várias vezes ao dia. A localização da ISS é atualizada em tempo real a cada poucos segundos. Imagens meteorológicas do GOES são atualizadas a cada 10-15 minutos.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <h3>Preciso de uma chave de API para usar o site?</h3>
                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Não, como usuário do site você não precisa de chaves de API. O site já está configurado com as chaves necessárias para acessar os serviços. Se você deseja implementar sua própria versão do site, precisará obter suas próprias chaves de API dos respectivos serviços.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <h3>Por que algumas imagens não carregam?</h3>
                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Isso pode ocorrer por vários motivos: (1) A API pode estar temporariamente indisponível; (2) Pode haver limitações na taxa de requisições; (3) Para algumas áreas ou períodos específicos, as imagens podem não estar disponíveis devido a condições climáticas ou manutenção de satélites.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <h3>Posso usar as imagens para fins comerciais?</h3>
                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>A maioria das imagens fornecidas pela NASA e outras agências governamentais está em domínio público e pode ser usada livremente, incluindo para fins comerciais. No entanto, é sempre recomendável verificar os termos de uso específicos de cada fonte de imagem e fornecer os devidos créditos.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                <h3>Como posso contribuir para o projeto?</h3>
                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <p>Valorizamos contribuições! Você pode ajudar reportando bugs, sugerindo novos recursos, melhorando a documentação ou contribuindo com código. Entre em contato conosco através da seção de contato para mais informações sobre como participar.</p>
            </div>
        </div>
    </div>
</section>
