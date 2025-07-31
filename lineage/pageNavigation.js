document.addEventListener("DOMContentLoaded", () => {
  const pageContainer = document.getElementById("pageContent");

  async function loadPage(url) {
    try {
      const res = await fetch(url);
      if (!res.ok) throw new Error(`Erro ao carregar ${url}: ${res.status}`);
      const html = await res.text();
      pageContainer.innerHTML = html;
    } catch (err) {
      console.error(err);
      pageContainer.innerHTML = "<p>Erro ao carregar conteúdo.</p>";
    }
  }

  const pageLinks = document.querySelectorAll(".navElite-link[data-content]");

  pageLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();

      pageLinks.forEach(l => l.classList.remove("active"));
      link.classList.add("active");

      const url = 'pages/' + link.dataset.content;

      if (url) {
        loadPage(url);
      }
    });
  });

  // Carrega home.php por padrão, só se existir e não estiver ativo ainda
  const defaultPage = document.querySelector('.navElite-link[data-content="home.php"]');
  if (defaultPage && !defaultPage.classList.contains("active")) {
    defaultPage.click();
  }
});
