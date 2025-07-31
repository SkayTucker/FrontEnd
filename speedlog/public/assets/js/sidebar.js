/* =================================================
   CONFIGURAÇÕES GERAIS
===================================================*/
const MOBILE_MAX = 1024;     // breakpoint em px
const WIDTHS = { EXPANDED: 20, COLLAPSED: 10, HEADER: 20 };

/* =================================================
   ELEMENTOS DOM
===================================================*/
const $ = (sel) => document.querySelector(sel);
const sidebar = $('#dashboardSidebar');
const container = $('.container');
const overlay = $('#sidebar-overlay');
const btnHamburger = $('#openSidebarBtn');
const btnCollapse = $('#toggleSidebarBtn');
const btnFix = $('#fixSidebarBtn');
const iconHamburger = btnHamburger?.querySelector('.fa-bars');
const iconClose = btnHamburger?.querySelector('.fa-times');

/* =================================================
   ESTADO (persistido para desktop)
===================================================*/
let isMobile = window.innerWidth <= MOBILE_MAX;
let isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
let isFixed = localStorage.getItem('sidebar-fixed') === 'true';

/* =================================================
   FUNÇÕES UTILITÁRIAS
===================================================*/
const setIcon = (open) => {
  if (!iconHamburger || !iconClose) return;
  iconHamburger.style.display = open ? 'none' : '';
  iconClose.style.display = open ? '' : 'none';
};

const updateContainer = () => {
  if (!container) return;

  if (isMobile) {
    container.style.cssText = `margin-left:0;padding-top:${WIDTHS.HEADER}px`;
  } else if (sidebar.classList.contains('fixed')) {
    const ml = sidebar.classList.contains('collapsed') ? WIDTHS.COLLAPSED : WIDTHS.EXPANDED;
    container.style.cssText = `margin-left:${ml}px;padding-top:0`;
  } else {
    container.style.cssText = 'margin-left:0;padding-top:0';
  }
};

const persist = () => {
  localStorage.setItem('sidebar-collapsed', isCollapsed);
  localStorage.setItem('sidebar-fixed', isFixed);
};

/* =================================================
   APLICA ESTADO INICIAL
===================================================*/
(function init() {
  if (!isMobile) {
    sidebar.classList.toggle('collapsed', isCollapsed);
    sidebar.classList.toggle('fixed', isFixed);
  } else {
    isCollapsed = isFixed = false;   // ignorar estado salvo em mobile
  }
  updateButtons();
  updateContainer();
})();

/* =================================================
   BOTÕES (visual / acessibilidade)
===================================================*/
function updateButtons() {
  // Botões desktop
  if (btnCollapse) {
    btnCollapse.innerHTML = isCollapsed
      ? '<i class="fas fa-angles-right"></i>'
      : '<i class="fas fa-angles-left"></i>';
    btnCollapse.style.display = isMobile || isFixed ? 'none' : '';
  }

  if (btnFix) {
    btnFix.style.display = isMobile ? 'none' : '';
    btnFix.style.color = isFixed ? 'var(--primary)' : '';
    btnFix.setAttribute('aria-pressed', isFixed.toString());
  }

  // Ícone hamburger / X
  setIcon(sidebar.classList.contains('open'));
}

/* =================================================
   AÇÕES (desktop & mobile)
===================================================*/
const openSidebar = () => {
  if (!isMobile || isFixed) return;
  sidebar.classList.add('open'); overlay.classList.add('active'); setIcon(true);
};
const closeSidebar = () => {
  if (!isMobile || isFixed) return;
  sidebar.classList.remove('open'); overlay.classList.remove('active'); setIcon(false);
};
const toggleSidebar = () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar();

/* Desktop: collapse / fix */
const toggleCollapse = () => {
  if (isMobile || isFixed) return;
  isCollapsed = !isCollapsed;
  sidebar.classList.toggle('collapsed', isCollapsed);
  persist(); updateButtons(); updateContainer();
};

const toggleFix = () => {
  if (isMobile) return;
  isFixed = !isFixed;
  sidebar.classList.toggle('fixed', isFixed);

  // sempre expandido quando fixar
  if (isFixed && isCollapsed) {
    isCollapsed = false;
    sidebar.classList.remove('collapsed');
  }
  closeSidebar();
  persist(); updateButtons(); updateContainer();
};

/* =================================================
   EVENTOS
===================================================*/
btnHamburger?.addEventListener('click', toggleSidebar);
overlay?.addEventListener('click', closeSidebar);
document.addEventListener('keydown', (e) => e.key === 'Escape' && closeSidebar());

btnCollapse?.addEventListener('click', toggleCollapse);
btnFix?.addEventListener('click', toggleFix);

/* === Resize com debounce === */
let resizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    const prev = isMobile;
    isMobile = window.innerWidth <= MOBILE_MAX;
    if (prev !== isMobile) {
      sidebar.classList.remove('open'); // sempre fechar ao trocar de modo
      setIcon(false);
      // reset colapse/fix em mobile
      if (isMobile) { sidebar.classList.remove('collapsed', 'fixed'); }
      updateButtons(); updateContainer();
    }
  }, 150);
});
