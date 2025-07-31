//dashboard.js
document.addEventListener('DOMContentLoaded', () => {
  // Supõe que modal.js e modal-inner.js já estão carregados antes
  initModal();
  window.initModalInnerJS = initModalInnerJS;

  window.openModal = function (url, title) {
    const modal = document.getElementById('modal-template');
    const modalBody = document.getElementById('modal-body');
    const modalTitle = document.getElementById('modal-title');

    if (!modal || !modalBody || !modalTitle) return;

    if (!modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
      modalBody.innerHTML = '';
      modalTitle.textContent = '';
    }

    modalTitle.textContent = title;
    modalBody.textContent = 'Carregando...';
    modal.classList.remove('hidden');

    fetch(`/Speedlog/src/includes/modal/modal-template.php?content=${encodeURIComponent(url)}&title=${encodeURIComponent(title)}`)
      .then(resp => {
        if (!resp.ok) throw new Error('Erro ao carregar conteúdo');
        return resp.text();
      })
      .then(html => {
        modalBody.innerHTML = html;
        const loadedTitle = modalBody.querySelector('#modal-title');
        if (loadedTitle) {
          modalTitle.textContent = loadedTitle.textContent;
        }
        initModalInnerJS();
      })
      .catch(() => {
        modalBody.innerHTML = '<p>Erro ao carregar conteúdo.</p>';
      });
  };
});

/* ================= modal-inner.js ================= */
/*  Este arquivo define initModalInnerJS no escopo global */
/*  e funciona sem usar módulos ES6 (export/import)        */

(function (global) {
  global.initModalInnerJS = function () {
    const modalBody = document.getElementById('modal-body');
    if (!modalBody) return;

    // Remove event listeners antigos
    modalBody.querySelectorAll('[data-modal-template-url]').forEach(btn => {
      btn.replaceWith(btn.cloneNode(true));
    });

    // Reatribui os eventos
    modalBody.querySelectorAll('[data-modal-template-url]').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const url = btn.getAttribute('data-modal-template-url');
        const title = btn.getAttribute('data-modal-title');
        if (global.openModal) {
          global.openModal(url, title);
        }
      });
    });

    // Inicializa funcionalidades específicas
    initSolicitacaoVeiculoJS();
  };

})(window);
