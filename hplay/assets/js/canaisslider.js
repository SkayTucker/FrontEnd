document.addEventListener("DOMContentLoaded", function () {
    const slideTrack = document.querySelector(".fightCardSliderTrack");
    const slideItems = Array.from(slideTrack.children);

    // Guarda a largura do bloco original
    const originalWidth = slideTrack.scrollWidth;

    // Clona os itens para duplicar e criar efeito infinito
    slideItems.forEach(item => {
        const clone = item.cloneNode(true);
        slideTrack.appendChild(clone);
    });

    let position = 0;
    const speed = 1; // px/frame (ajuste fino depois)

    function animateSlide() {
        position -= speed;
        slideTrack.style.transform = `translateX(${position}px)`;

        // Quando atingir a largura do bloco original, reseta
        if (Math.abs(position) >= originalWidth) {
            position = 0;
        }

        requestAnimationFrame(animateSlide);
    }

    animateSlide();
});
