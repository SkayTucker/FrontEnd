document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalMaes');
    const closeBtn = document.querySelector('.modal-maes-close');
  
    // Fechar clicando no botão ✖
    closeBtn.addEventListener('click', function () {
      modal.style.display = 'none';
    });
  
    // Fechar clicando fora da imagem
    window.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  });
  