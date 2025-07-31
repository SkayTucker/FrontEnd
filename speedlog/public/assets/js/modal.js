(function (global) {
  // Espera o DOM estar completamente carregado
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-template');
    if (!modal) return;

    const modalTitle = modal.querySelector('#modal-title');
    const modalBody = modal.querySelector('#modal-body');
    const closeModalBtn = modal.querySelector('#close-modal');

    function openModal(contentUrl, title) {
      if (!modal.classList.contains('hidden')) closeModal();

      modalTitle.textContent = title;
      modalBody.innerHTML = '<p>Carregando...</p>';
      modal.classList.remove('hidden');

      const url = `/speedlog/src/modal/modal-template.php?content=${encodeURIComponent(contentUrl)}&title=${encodeURIComponent(title)}`;

      fetch(url)
        .then(resp => {
          if (!resp.ok) throw new Error('Erro ao carregar conteúdo');
          return resp.text();
        })
        .then(html => {
          modalBody.innerHTML = html;

          // Atualiza título se houver override dentro do conteúdo
          const loadedTitle = modalBody.querySelector('.modal-title');
          if (loadedTitle && loadedTitle.textContent.trim()) {
            modalTitle.textContent = loadedTitle.textContent.trim();
            loadedTitle.remove();
          }

          // Executa scripts inline dentro do conteúdo carregado
          modalBody.querySelectorAll('script').forEach(script => {
            const newScript = document.createElement('script');
            if (script.src) {
              newScript.src = script.src;
            } else {
              newScript.textContent = script.textContent;
            }
            document.body.appendChild(newScript);
            script.remove();
          });

          // Reativa botões de fechar dentro do conteúdo
          modalBody.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', closeModal);
          });

          if (typeof global.initModalInnerJS === 'function') {
            global.initModalInnerJS();
          }
        })
        .catch(() => {
          modalBody.innerHTML = '<p>Erro ao carregar o conteúdo do modal.</p>';
        });
    }

    function closeModal() {
      modal.classList.add('hidden');
      modalTitle.textContent = '';
      modalBody.innerHTML = '';
    }

    closeModalBtn?.addEventListener('click', closeModal);

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
        closeModal();
      }
    });

    // Delegação para links/botões que disparam o modal
    document.body.addEventListener('click', e => {
      const el = e.target.closest('[data-modal-template-url]');
      if (el) {
        e.preventDefault();
        const url = el.getAttribute('data-modal-template-url');
        const title = el.getAttribute('data-modal-title') || 'Modal';
        openModal(url, title);
      }
    });

    // Exporta funções globais
    global.openModal = openModal;
    global.closeModal = closeModal;
    global.initModal = () => true;
    global.initModalInnerJS = () => { };
  });
})(window);
