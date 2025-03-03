document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll('.timeline-section');
    const markers = document.querySelectorAll('.timeline-marker');

    // Função para verificar qual seção está visível
    function checkVisibility() {
        sections.forEach((section, index) => {
            const rect = section.getBoundingClientRect();
            if (rect.top < window.innerHeight * 0.8 && rect.bottom > 0) {
                section.classList.add('show');
                markers[index].classList.add('active');
            } else {
                section.classList.remove('show');
                markers[index].classList.remove('active');
            }
        });
    }

    // Detectar o scroll e aplicar o efeito
    window.addEventListener('scroll', checkVisibility);
    checkVisibility(); // Aplica as animações logo após o carregamento

    // Scroll para a seção ao clicar no marcador
    markers.forEach(marker => {
        marker.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);
            targetSection.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
