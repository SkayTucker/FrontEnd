document.addEventListener("DOMContentLoaded", () => {
  const rankTable = document.getElementById("rankTable");

  async function loadRank(url) {
    try {
      rankTable.innerHTML = "<p>Loading...</p>";  // mostra loading antes do fetch
      const res = await fetch("config/" + url);
      if (!res.ok) throw new Error(`Erro ao carregar ${url}: ${res.status}`);
      const html = await res.text();
      rankTable.innerHTML = html;
    } catch (err) {
      console.error(err);
      rankTable.innerHTML = "<p>Erro ao carregar ranking.</p>";
    }
  }


  const rankLinks = document.querySelectorAll(".rank-link[data-rank]");

  rankLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();

      rankLinks.forEach(l => l.classList.remove("active"));
      link.classList.add("active");

      loadRank(link.dataset.rank);
    });
  });

  // Carrega rank pvp.php por padrão
  const defaultRank = document.querySelector('.rank-link[data-rank="rank/pvp.php"]');
  if (defaultRank) defaultRank.click();
});
