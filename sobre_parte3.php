<?php
/**
 * SatéliteVision - Página Sobre (Parte 3)
 * Formulário de contato e rodapé
 */

// Esta parte deve ser incluída após sobre_parte2.php
?>

<!-- Seção de Contato -->
<section class="featured-section">
    <h2 class="section-title">Entre em Contato</h2>
    <p class="section-description">Tem dúvidas, sugestões ou encontrou algum problema? Entre em contato conosco!</p>
    
    <div class="contact-container">
        <div class="contact-info">
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div class="contact-text">
                    <h3>Email</h3>
                    <p>contato@satelitevision.com</p>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="contact-text">
                    <h3>Localização</h3>
                    <p>São Paulo, Brasil</p>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-globe"></i></div>
                <div class="contact-text">
                    <h3>Website</h3>
                    <p>www.satelitevision.com</p>
                </div>
            </div>
            
            <div class="contact-social">
                <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
            </div>
        </div>
        
        <div class="contact-form">
            <form id="contact-form" action="api/send_contact.php" method="post">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Assunto</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Mensagem</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar Mensagem <i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</section>

<!-- Scripts específicos da página -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const toggle = item.querySelector('.faq-toggle');
        
        question.addEventListener('click', function() {
            // Fecha todos os outros itens
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.faq-answer').style.maxHeight = null;
                    otherItem.querySelector('.faq-toggle i').className = 'fas fa-chevron-down';
                }
            });
            
            // Alterna o item atual
            item.classList.toggle('active');
            
            if (item.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                toggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
            } else {
                answer.style.maxHeight = null;
                toggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
            }
        });
    });
    
    // Formulário de contato
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            // Aqui seria feito o envio do formulário via AJAX
            // Por enquanto, apenas simula o envio
            
            // Mostra uma mensagem de carregamento
            const formContainer = contactForm.parentElement;
            const originalContent = formContainer.innerHTML;
            
            formContainer.innerHTML = `
                <div class="loading-effect">
                    <div class="loader">
                        <div class="loader-ring"></div>
                        <div class="loader-planet"></div>
                    </div>
                    <p>Enviando mensagem...</p>
                </div>
            `;
            
            // Simula o tempo de processamento
            setTimeout(function() {
                formContainer.innerHTML = `
                    <div class="success-message">
                        <div class="success-icon"><i class="fas fa-check-circle"></i></div>
                        <h3>Mensagem Enviada!</h3>
                        <p>Obrigado por entrar em contato, ${name}! Responderemos em breve.</p>
                        <button id="new-message" class="btn btn-secondary">Enviar Nova Mensagem</button>
                    </div>
                `;
                
                // Botão para enviar nova mensagem
                document.getElementById('new-message').addEventListener('click', function() {
                    formContainer.innerHTML = originalContent;
                    
                    // Reinicia os listeners do formulário
                    document.getElementById('contact-form').addEventListener('submit', arguments.callee);
                });
            }, 2000);
        });
    }
});
</script>

<?php
// Renderiza o rodapé
render_footer();
?>
