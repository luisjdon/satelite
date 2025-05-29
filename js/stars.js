/**
 * SatéliteVision - Animação de Estrelas
 * Cria um fundo animado de estrelas usando Three.js
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializa a animação de estrelas
    initStars();
});

/**
 * Inicializa a animação de estrelas
 */
function initStars() {
    // Verifica se o Three.js está disponível
    if (typeof THREE === 'undefined') {
        console.error('Three.js não está disponível');
        return;
    }
    
    // Obtém o elemento de fundo
    const starsBackground = document.querySelector('.stars-background');
    
    if (!starsBackground) return;
    
    // Configurações
    const particleCount = 2000;
    const particleSize = 0.5;
    const particleMinDistance = 10;
    const particleMaxDistance = 1000;
    const cameraZ = 300;
    
    // Cria a cena
    const scene = new THREE.Scene();
    
    // Cria a câmera
    const camera = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight,
        1,
        particleMaxDistance * 2
    );
    camera.position.z = cameraZ;
    
    // Cria o renderer
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor(0x000000, 0);
    starsBackground.appendChild(renderer.domElement);
    
    // Cria as partículas
    const particles = new THREE.BufferGeometry();
    const positions = [];
    const sizes = [];
    const colors = [];
    
    // Cores para as estrelas
    const starColors = [
        new THREE.Color(0xffffff), // Branco
        new THREE.Color(0xadd8e6), // Azul claro
        new THREE.Color(0xffd700), // Amarelo
        new THREE.Color(0xff8c00), // Laranja
        new THREE.Color(0xff4500)  // Vermelho
    ];
    
    // Gera posições aleatórias para as estrelas
    for (let i = 0; i < particleCount; i++) {
        // Posição
        const x = Math.random() * 2000 - 1000;
        const y = Math.random() * 2000 - 1000;
        const z = Math.random() * (particleMaxDistance - particleMinDistance) + particleMinDistance;
        
        positions.push(x, y, z);
        
        // Tamanho
        const size = Math.random() * particleSize + 0.1;
        sizes.push(size);
        
        // Cor
        const color = starColors[Math.floor(Math.random() * starColors.length)];
        colors.push(color.r, color.g, color.b);
    }
    
    // Adiciona os atributos à geometria
    particles.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    particles.setAttribute('size', new THREE.Float32BufferAttribute(sizes, 1));
    particles.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));
    
    // Material para as partículas
    const material = new THREE.PointsMaterial({
        size: 1,
        vertexColors: true,
        transparent: true,
        opacity: 0.8,
        sizeAttenuation: true
    });
    
    // Cria o sistema de partículas
    const particleSystem = new THREE.Points(particles, material);
    scene.add(particleSystem);
    
    // Função de animação
    function animate() {
        requestAnimationFrame(animate);
        
        // Rotação lenta do sistema de partículas
        particleSystem.rotation.x += 0.0001;
        particleSystem.rotation.y += 0.0002;
        
        // Movimento das estrelas em direção à câmera
        const positions = particles.attributes.position.array;
        
        for (let i = 0; i < positions.length; i += 3) {
            // Move a estrela em direção à câmera
            positions[i + 2] -= 0.2;
            
            // Se a estrela passou pela câmera, reposiciona-a longe
            if (positions[i + 2] < -cameraZ) {
                positions[i] = Math.random() * 2000 - 1000;
                positions[i + 1] = Math.random() * 2000 - 1000;
                positions[i + 2] = particleMaxDistance;
            }
        }
        
        particles.attributes.position.needsUpdate = true;
        
        renderer.render(scene, camera);
    }
    
    // Redimensiona o canvas quando a janela é redimensionada
    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    }
    
    window.addEventListener('resize', onWindowResize);
    
    // Inicia a animação
    animate();
}
