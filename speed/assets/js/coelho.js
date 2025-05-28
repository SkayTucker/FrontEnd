document.addEventListener("DOMContentLoaded", function () {
    function criarCoelho() {
        const coelho = document.createElement("img");
        coelho.src = "assets/img/coelho.png"; // Substitua pela URL ou caminho da imagem do coelho
        coelho.alt = "Coelho da Páscoa";
        coelho.style.position = "fixed";
        coelho.style.width = "180px"; // Ajuste o tamanho do coelho
        coelho.style.height = "auto";
        coelho.style.zIndex = "1000";
        coelho.style.opacity = "0";
        coelho.style.transition = "opacity 0.5s, transform 0.5s ease-out";
        coelho.style.pointerEvents = "none"; // Impede interação com o coelho

        document.body.appendChild(coelho);

        function moverCoelho() {
            const larguraTela = window.innerWidth;
            const alturaTela = window.innerHeight;
            
            // Escolhe uma das bordas: top, bottom, left, right
            const bordas = ["top", "left"];
            const bordaEscolhida = bordas[Math.floor(Math.random() * bordas.length)];
            
            let x = 0, y = 0, rotacao = "0deg", translateX = "0", translateY = "0";

            switch (bordaEscolhida) {
                case "top":
                    x = Math.random() * larguraTela;
                    y = 15; // Fora da tela (em cima)
                    rotacao = "180deg";
                    translateY = "30px"; // Move para baixo ao aparecer
                    break;
                case "left":
                    x = -20; // Fora da tela (esquerda)
                    y = Math.random() * alturaTela;
                    rotacao = "90deg";
                    translateX = "30px"; // Move para a direita ao aparecer
                    break;
                    break;
            }

            coelho.style.left = `${x}px`;
            coelho.style.top = `${y}px`;
            coelho.style.transform = `rotate(${rotacao}) translate(${translateX}, ${translateY})`;
            coelho.style.opacity = "1";

            setTimeout(() => {
                coelho.style.opacity = "0";
                coelho.style.transform = `rotate(${rotacao}) translate(0, 0)`;
            }, 2000); // Desaparece após 2 segundos
        }

        setInterval(moverCoelho, 6000); // Aparece a cada 3 segundos
        moverCoelho(); // Primeira aparição imediata
    }

    criarCoelho();
});