document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenu = document.getElementById('close-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('.main-header');
    const navLinks = document.querySelectorAll('.nav-links li a');
    const sections = document.querySelectorAll('section[id]');

    /*** Menu Mobile ***/
    function openMobileMenu() {
        mobileMenu.classList.add('open');
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
    }

    function closeMobileMenuOnLinkClick() {
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
    }

    /*** Header Transparente ***/
    function toggleHeaderBackground() {
        header.classList.toggle('transparent', window.scrollY === 0);
    }

    /*** Menu Ativo com Scroll ***/
    function setupSectionObserver() {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                const id = entry.target.getAttribute('id');
                const link = document.querySelector(`.nav-links li a[href="#${id}"]`);

                if (entry.isIntersecting) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    if (link) link.classList.add('active');
                }
            });
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.6
        });

        sections.forEach(section => observer.observe(section));
    }

    /*** Inicializações ***/
    menuToggle.addEventListener('click', openMobileMenu);
    closeMenu.addEventListener('click', closeMobileMenu);
    closeMobileMenuOnLinkClick();

    toggleHeaderBackground();
    window.addEventListener('scroll', toggleHeaderBackground);

    setupSectionObserver();
});
