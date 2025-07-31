document.addEventListener("DOMContentLoaded", function () {
    function loadPage(page) {
        fetch("pages/" + page + ".php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("content").innerHTML = data;
            })
            .catch(error => console.error("Erro ao carregar a página:", error));
    }

    // Carrega a página inicial (home) ao iniciar o painel
    loadPage("home");

    // Adiciona evento de clique para cada link do menu
    document.querySelectorAll(".siteMenu a").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const page = this.getAttribute("data-page");
            loadPage(page);
        });
    });
});
