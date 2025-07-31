document.addEventListener("DOMContentLoaded", function () {
    function loadPageContent(pageContent) {
        fetch("pages/" + pageContent)
            .then(response => response.text())
            .then(data => {
                document.getElementById("pageContent").innerHTML = data;
                window.scrollTo(0, 0);
            })
            .catch(error => {
                document.getElementById("pageContent").innerHTML = "<p>Erro ao carregar página.</p>";
                console.error("Erro ao carregar a página:", error);
            });
    }

    document.querySelectorAll(".navElite-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const page = this.getAttribute("data-content");
            loadPageContent(page);
        });
    });

    loadPageContent("home.php");
});

document.addEventListener("DOMContentLoaded", function () {
    const videos = ["assets/media/kamael.mp4", "assets/media/portal.mp4"];
    const video1 = document.getElementById("video1");
    const video2 = document.getElementById("video2");
    let current = 0;

    function changeVideo() {
        current = (current + 1) % videos.length;

        const active = video1.classList.contains("active") ? video1 : video2;
        const hidden = active === video1 ? video2 : video1;
        const source = hidden.querySelector("source");
        source.src = videos[current];
        hidden.load();

        hidden.oncanplay = () => {
            active.classList.remove("active");
            hidden.classList.add("active");
            hidden.play();
        };
    }

    setInterval(changeVideo, 15000);
});


