// Header Scroll Behavior
// Animações gerais
document.addEventListener("DOMContentLoaded", () => {
  const header = document.getElementById("main-header");
  let lastScrollY = 0;

  window.addEventListener("scroll", () => {
    if (window.scrollY === 0) {
      header.classList.add("full");
    } else if (window.scrollY > lastScrollY) {
      header.classList.remove("full");
    }
    lastScrollY = window.scrollY;
  });
});

// Animações gerais
document.addEventListener("DOMContentLoaded", () => {
  const animateElements = document.querySelectorAll(".animate");

  animateElements.forEach(el => el.classList.add("start"));

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  animateElements.forEach(element => observer.observe(element));
});


// Smooth Scrolling for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const targetId = this.getAttribute('href').substring(1);
    const targetElement = document.getElementById(targetId);
    if (targetElement) {
      targetElement.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

