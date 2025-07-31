document.addEventListener("DOMContentLoaded", () => {
    // Containers principais onde o conteúdo será inserido
    const mainContent = document.getElementById("pageContent");
    const rankContent = document.getElementById("rankTable");

    // Função para carregar conteúdo via fetch e inserir no container
    async function loadContent(url, container) {
        try {
            console.log(`Carregando conteúdo de: ${url}`); // DEBUG
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Erro ao carregar: ${url} (status ${response.status})`);
            const html = await response.text();
            console.log('Conteúdo recebido:', html); // DEBUG
            container.innerHTML = html;
        } catch (err) {
            console.error(err);
            container.innerHTML = `<p>Erro ao carregar conteúdo.</p>`;
        }
    }


    // Função para configurar navegação dinâmica em links
    // linksSelector: seletor dos links
    // container: container onde o conteúdo será injetado
    // urlPrefix: prefixo da URL para carregar (ex: "config/")
    function setupNavigation(linksSelector, container, urlPrefix = "") {
        const links = document.querySelectorAll(linksSelector);

        links.forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();

                // Atualiza classe active nos links
                links.forEach(l => l.classList.remove("active"));
                link.classList.add("active");

                // Define URL baseada no atributo data-content ou data-rank
                const url = urlPrefix + (link.dataset.content || link.dataset.rank);
                loadContent(url, container);
            });
        });

        return links;
    }

    // Inicializa navegação das páginas principais
    setupNavigation(".navElite-link[data-content]", mainContent);

    // Inicializa navegação dos ranks com prefixo "config/"
    setupNavigation(".rank-link[data-rank]", rankContent, "config/");

    // Aciona carregamento padrão da página inicial (home.php)
    const defaultPage = document.querySelector('.navElite-link[data-content="home.php"]');
    if (defaultPage && !defaultPage.classList.contains("active")) {
        defaultPage.click();
    }

    // Aciona carregamento padrão do ranking PvP (rank/pvp.php)
    const defaultRank = document.querySelector('.rank-link[data-rank="rank/pvp.php"]');
    if (defaultRank && !defaultRank.classList.contains("active")) {
        defaultRank.click();
    }
});
