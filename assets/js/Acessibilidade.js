//INICIA Botão DarkMode
window.onload = setDarkModeBtn;
/* Seleção de Elementos */
const tagAcessibility = document.querySelectorAll('a, p, h3, h4, h5'); // Seleciona tags de acessibilidade        

/* Botões */
const darkBtn = document.getElementById('DarkBtn');

//======================================  Dark Mode

// Função para alternar entre o modo escuro e claro
function darkMode() {
    document.body.classList.toggle("dark-mode");

    // Alterna as classes do botão entre 'light-mode-btn' e 'dark-mode-btn'
    if (document.body.classList.contains('dark-mode')) {
        darkBtn.classList.remove('light-mode-btn');
        darkBtn.classList.add('dark-mode-btn');
    } else {
        darkBtn.classList.remove('dark-mode-btn');
        darkBtn.classList.add('light-mode-btn');
    }
}

// Função para definir o estado inicial do botão ao carregar a página
function setDarkModeBtn() {
    const darkBtn = document.getElementById('DarkBtn');
    // Verifica se o modo escuro está ativo ao carregar a página
    if (document.body.classList.contains('dark-mode')) {
        darkBtn.classList.add('dark-mode-btn');
    } else {
        darkBtn.classList.add('light-mode-btn');
    }
}



//====================================== FIM DARK MODE

// Função genérica para ajustar o zoom
function adjustZoom(factor) {
    tagAcessibility.forEach(function(element) {
        // Obtém o tamanho da fonte atual
        var currentSize = window.getComputedStyle(element, null).getPropertyValue('font-size');
        // Remove o "px" e converte para um número
        var newSize = parseFloat(currentSize) + factor; // Ajusta o tamanho da fonte pelo fator passado
        // Define o novo tamanho de fonte
        element.style.fontSize = newSize + 'px';
    });
}

// Funções de Zoom
function zoomIn() {
    adjustZoom(2); // Aumenta o tamanho da fonte em 2px
}

function zoomOut() {
    adjustZoom(-2); // Diminui o tamanho da fonte em 2px
}
