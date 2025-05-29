<?php
/**
 * SatéliteVision - Página da ISS (Parte 2)
 * Informações sobre a Estação Espacial Internacional
 */

// Esta parte deve ser incluída após iss_parte1.php
?>

<!-- Seção de Informações da ISS -->
<section class="featured-section">
    <h2 class="section-title">Sobre a ISS</h2>
    <p class="section-description">A Estação Espacial Internacional é o maior objeto já construído pelo homem no espaço e serve como laboratório de pesquisa em microgravidade.</p>
    
    <div class="iss-info-container">
        <div class="iss-image">
            <img src="img/iss-structure.jpg" alt="Estrutura da Estação Espacial Internacional" class="animate-float">
        </div>
        
        <div class="iss-specs">
            <div class="iss-spec-item">
                <div class="iss-spec-icon"><i class="fas fa-ruler"></i></div>
                <div class="iss-spec-details">
                    <h4>Dimensões</h4>
                    <p>109m x 73m (tamanho aproximado de um campo de futebol)</p>
                </div>
            </div>
            <div class="iss-spec-item">
                <div class="iss-spec-icon"><i class="fas fa-weight-hanging"></i></div>
                <div class="iss-spec-details">
                    <h4>Massa</h4>
                    <p>Aproximadamente 420.000 kg</p>
                </div>
            </div>
            <div class="iss-spec-item">
                <div class="iss-spec-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="iss-spec-details">
                    <h4>Início da Construção</h4>
                    <p>20 de novembro de 1998</p>
                </div>
            </div>
            <div class="iss-spec-item">
                <div class="iss-spec-icon"><i class="fas fa-users"></i></div>
                <div class="iss-spec-details">
                    <h4>Tripulação</h4>
                    <p>Normalmente 6 astronautas</p>
                </div>
            </div>
            <div class="iss-spec-item">
                <div class="iss-spec-icon"><i class="fas fa-flag"></i></div>
                <div class="iss-spec-details">
                    <h4>Países Participantes</h4>
                    <p>EUA, Rússia, Japão, Canadá, e países da ESA</p>
                </div>
            </div>
            <div class="iss-spec-item">
                <div class="iss-spec-icon"><i class="fas fa-solar-panel"></i></div>
                <div class="iss-spec-details">
                    <h4>Energia</h4>
                    <p>Painéis solares gerando 75 a 90 kilowatts</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seção de Módulos da ISS -->
<section class="featured-section">
    <h2 class="section-title">Módulos Principais</h2>
    <p class="section-description">A ISS é composta por vários módulos interconectados, cada um com funções específicas.</p>
    
    <div class="modules-grid">
        <div class="module-card">
            <div class="module-image">
                <img src="img/module-zarya.jpg" alt="Módulo Zarya">
            </div>
            <div class="module-info">
                <h3>Zarya</h3>
                <p>Primeiro módulo lançado, fornece energia elétrica, armazenamento, propulsão e orientação.</p>
                <div class="module-meta">
                    <span class="module-country">Rússia</span>
                    <span class="module-date">Lançado em 1998</span>
                </div>
            </div>
        </div>
        
        <div class="module-card">
            <div class="module-image">
                <img src="img/module-unity.jpg" alt="Módulo Unity">
            </div>
            <div class="module-info">
                <h3>Unity</h3>
                <p>Módulo de conexão que serve como passagem entre os segmentos americano e russo da estação.</p>
                <div class="module-meta">
                    <span class="module-country">EUA</span>
                    <span class="module-date">Lançado em 1998</span>
                </div>
            </div>
        </div>
        
        <div class="module-card">
            <div class="module-image">
                <img src="img/module-destiny.jpg" alt="Módulo Destiny">
            </div>
            <div class="module-info">
                <h3>Destiny</h3>
                <p>Laboratório principal dos EUA para experimentos científicos em ambiente de microgravidade.</p>
                <div class="module-meta">
                    <span class="module-country">EUA</span>
                    <span class="module-date">Lançado em 2001</span>
                </div>
            </div>
        </div>
        
        <div class="module-card">
            <div class="module-image">
                <img src="img/module-columbus.jpg" alt="Módulo Columbus">
            </div>
            <div class="module-info">
                <h3>Columbus</h3>
                <p>Laboratório de pesquisa europeu para experimentos em ciências dos materiais e biologia.</p>
                <div class="module-meta">
                    <span class="module-country">ESA</span>
                    <span class="module-date">Lançado em 2008</span>
                </div>
            </div>
        </div>
        
        <div class="module-card">
            <div class="module-image">
                <img src="img/module-kibo.jpg" alt="Módulo Kibo">
            </div>
            <div class="module-info">
                <h3>Kibo</h3>
                <p>Laboratório japonês com plataforma externa para experimentos expostos ao vácuo espacial.</p>
                <div class="module-meta">
                    <span class="module-country">Japão</span>
                    <span class="module-date">Lançado em 2008</span>
                </div>
            </div>
        </div>
        
        <div class="module-card">
            <div class="module-image">
                <img src="img/module-cupola.jpg" alt="Módulo Cupola">
            </div>
            <div class="module-info">
                <h3>Cupola</h3>
                <p>Observatório com sete janelas que permite aos astronautas uma vista panorâmica da Terra e do espaço.</p>
                <div class="module-meta">
                    <span class="module-country">ESA</span>
                    <span class="module-date">Lançado em 2010</span>
                </div>
            </div>
        </div>
    </div>
</section>
