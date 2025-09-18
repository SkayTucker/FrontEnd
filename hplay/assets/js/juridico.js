function showLegalModal() {
  document.getElementById('legalModal').style.display = 'block';
}

function closeLegalModal() {
  document.getElementById('legalModal').style.display = 'none';
}

// Fechar modal ao clicar fora
window.onclick = function (event) {
  const modal = document.getElementById('legalModal');
  if (event.target == modal) {
    modal.style.display = 'none';
  }
}

// Fechar modal ao apertar ESC
document.onkeydown = function (event) {
  if (event.key === "Escape") {
    closeLegalModal();
  }
}

const juridicoBtn = document.querySelector('.juridico-toggle');
const juridicoContent = document.querySelector('.juridicoContent');
const arrow = juridicoBtn.querySelector('.arrow');

juridicoBtn.addEventListener('click', () => {
  juridicoContent.classList.toggle('open');
  arrow.style.transform = juridicoContent.classList.contains('open')
    ? 'rotate(180deg)'
    : 'rotate(0deg)';
});
