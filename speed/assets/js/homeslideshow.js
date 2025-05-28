document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll('.homeSlideshow .slide');
  let currentSlide = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
  }

  document.querySelector('.nextSlide').addEventListener('click', nextSlide);
  document.querySelector('.prevSlide').addEventListener('click', prevSlide);

  showSlide(currentSlide); // Exibe o primeiro slide
  setInterval(nextSlide, 5000); // Slide automático
});

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.remove('active');

    const progress = slide.querySelector('.slide-progress');
    if (progress) {
      progress.style.animation = 'none'; // reseta
      progress.offsetHeight; // força reflow
      progress.style.animation = null;   // reaplica
    }
  });

  slides[index].classList.add('active');
}
