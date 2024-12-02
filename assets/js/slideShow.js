// Seleciona os botões do menu e o contêiner de conteúdo
const menuButtons = document.querySelectorAll('.menu button');
const contentDiv = document.querySelector('.content');

// Função para carregar uma página dinamicamente
function loadPage(page) {
    fetch(`pages/${page}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao carregar a página: ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            contentDiv.innerHTML = html;
        })
        .catch(error => {
            contentDiv.innerHTML = `<p style="color: red;">${error.message}</p>`;
        });
}

// Adiciona o evento de clique aos botões
menuButtons.forEach(button => {
    button.addEventListener('click', () => {
        const page = button.getAttribute('data-page');
        loadPage(page);
    });
});
