const slider = document.querySelector(".slider");
const slidesPlan = document.querySelectorAll(".slider .slide");
const sliderButtons = document.querySelectorAll(".slider-btn");
const prevPlanBtn = document.querySelector(".prevPlan");
const nextPlanBtn = document.querySelector(".nextPlan");

let currentPlanSlide = 0;

// Ativa a animação dos planos
function animatePlans(slideElement) {
  const allPlans = slideElement.querySelectorAll(".planFibraBasic, .planFibra, .plan");
  allPlans.forEach(plan => plan.classList.remove("plan-active"));
  allPlans.forEach((plan, i) => {
    setTimeout(() => plan.classList.add("plan-active"), i * 250);
  });
}

// Mostra o slide correspondente
function showPlanSlide(index) {
  slider.style.transform = `translateX(-${index * 100}%)`;
  slider.style.transition = "transform 0.5s ease-in-out";
  sliderButtons.forEach(btn => btn.classList.remove("active"));
  sliderButtons[index].classList.add("active");
  animatePlans(slidesPlan[index]);
}

// Observer para ativar ao visualizar
const planosSection = document.querySelector("#planos");
const observer = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      showPlanSlide(currentPlanSlide);
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.3 }); // menor sensibilidade (mobile-friendly)

observer.observe(planosSection);

// Fallback: garante que o primeiro slide será exibido mesmo que o observer não ative
window.addEventListener("load", () => {
  setTimeout(() => {
    const hasActive = document.querySelector(".plan-active");
    if (!hasActive) {
      showPlanSlide(currentPlanSlide);
    }
  }, 300); // dá tempo pro DOM estabilizar
});

// Botões de navegação
sliderButtons.forEach((button, index) => {
  button.addEventListener("click", () => {
    currentPlanSlide = index;
    showPlanSlide(currentPlanSlide);
  });
});

nextPlanBtn.addEventListener("click", () => {
  currentPlanSlide = (currentPlanSlide + 1) % slidesPlan.length;
  showPlanSlide(currentPlanSlide);
});

prevPlanBtn.addEventListener("click", () => {
  currentPlanSlide = (currentPlanSlide - 1 + slidesPlan.length) % slidesPlan.length;
  showPlanSlide(currentPlanSlide);
});
