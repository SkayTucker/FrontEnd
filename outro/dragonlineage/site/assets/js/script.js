function loadPage(page) {
    const contentElement = document.getElementById('main-content');
    const url = `pages/${page}.php`;

    // Utilizando fetch para carregar o conteúdo da página
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao carregar a página: ${response.statusText}`);
            }
            return response.text();
        })
        .then(data => {
            contentElement.innerHTML = data;
            // Salvando a página atual no armazenamento local
            localStorage.setItem('currentPage', page);
        })
        .catch(error => {
            console.error(error);
            contentElement.innerHTML = '<h2>Erro ao carregar a página.</h2>';
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const savedPage = localStorage.getItem('currentPage');
    const firstVisit = localStorage.getItem('firstVisit');

    // Carregue a página home.php se for a primeira visita ou se firstVisit não estiver definido
    loadPage(firstVisit === 'true' ? 'home' : savedPage);

    // Adicione eventos de clique para carregar páginas e salvar o estado
    const buttons = document.querySelectorAll('aside button');
    buttons.forEach(button => {
        button.addEventListener('click', (e) => {
            // Remova o emoji do nome da página antes de chamar a função loadPage
            const page = e.currentTarget.textContent.replace(/[^a-zA-Z]/g, '').toLowerCase();
            loadPage(page);
        });
    });
});


