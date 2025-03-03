function loadPage(page) {
    fetch("pages/" + page + ".php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => {
            console.error("Erro ao carregar a página:", error);
        });
}





document.addEventListener("DOMContentLoaded", function () {
    verificarPersonagem();
});



function verificarPersonagem() {
    fetch("pages/check_character.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "no_character") {
                window.location.href = "pages/criar_personagem.php";
            } else {
                console.log("Personagem carregado:", data.character);
            }
        })
        .catch(error => console.error("Erro ao verificar personagem:", error));
}

function carregarPagina(pagina) {
    fetch("pages/" + pagina + ".php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => console.error("Erro ao carregar página:", error));
}
