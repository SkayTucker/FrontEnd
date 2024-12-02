const slides = document.querySelectorAll('.slide');
let currentIndex = 0;

const showSlide = (index) => {
  gsap.to(slides[currentIndex], { 
    duration: 1, 
    opacity: 0, 
    x: -100, 
    onComplete: () => slides[currentIndex].classList.remove('active') 
  });
  
  currentIndex = index;

  gsap.fromTo(slides[currentIndex], 
    { opacity: 0, x: 100 }, 
    { opacity: 1, x: 0, duration: 1, onStart: () => slides[currentIndex].classList.add('active') }
  );
};

document.getElementById('next').addEventListener('click', () => {
  const nextIndex = (currentIndex + 1) % slides.length;
  showSlide(nextIndex);
});

document.getElementById('prev').addEventListener('click', () => {
  const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
  showSlide(prevIndex);
});
