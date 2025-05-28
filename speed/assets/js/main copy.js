// Header Scroll Behavior
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

// Slideshow Header
const slides = document.querySelectorAll('.homeSlideshow .slide');
let currentSlide = 0;

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.remove('active');
    slide.style.opacity = i === index ? '1' : '0';
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

// Adiciona eventos aos botões de navegação
document.querySelector('.nextSlide').addEventListener('click', nextSlide);
document.querySelector('.prevSlide').addEventListener('click', prevSlide);

// Troca automática de slides a cada 5 segundos
setInterval(nextSlide, 5000);

// Exibe o primeiro slide ao carregar
showSlide(currentSlide);

// SLIDE PLANS
const slider = document.querySelector(".slider");
const slidesPlan = document.querySelectorAll(".slider .slide");
const sliderButtons = document.querySelectorAll(".slider-btn");
const prevPlanBtn = document.querySelector(".prevPlan");
const nextPlanBtn = document.querySelector(".nextPlan");

let currentPlanSlide = 0;

// Função para aplicar animações nos planos de forma sequencial
function animatePlans(slideElement) {
  const allPlans = slideElement.querySelectorAll(".planFibraBasic, .planFibra, .plan");
  allPlans.forEach(plan => plan.classList.remove("plan-active"));

  allPlans.forEach((plan, i) => {
    setTimeout(() => {
      plan.classList.add("plan-active");
    }, i * 250); // tempo ajustado pra efeito carta
  });
}


// Mostra o slide e aplica a animação nos planos
function showPlanSlide(index) {
  // Mover o slider
  slider.style.transform = `translateX(-${index * 100}%)`;
  slider.style.transition = "transform 0.5s ease-in-out";

  // Atualiza os botões ativos
  sliderButtons.forEach((btn) => btn.classList.remove("active"));
  sliderButtons[index].classList.add("active");

  // Aplica animação nos planos do slide atual
  const currentSlide = slidesPlan[index];
  animatePlans(currentSlide);
}
// Ativa a animação dos planos apenas quando #planos estiver visível (60%)
const planosSection = document.querySelector("#planos");

const observer = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // Garante que a animação só aconteça uma vez
      showPlanSlide(currentPlanSlide);
      observer.unobserve(entry.target); // Para de observar depois de ativar
    }
  });
}, {
  threshold: 0.6 // 60% da section visível
});

observer.observe(planosSection);

// Eventos dos botões de navegação
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

// Inicia o primeiro slide visível
//showPlanSlide(currentPlanSlide);


/* ZAP ZAP */
// WhatsApp Support and Request Visit
document.getElementById("whatsapp-support").addEventListener("click", () => {
  window.open("https://wa.me/5511999999999?text=Olá,%20preciso%20de%20suporte%20técnico!", "_blank");
});

document.getElementById("request-visit").addEventListener("click", () => {
  alert("Nossa equipe entrará em contato para agendar a visita técnica.");
});

function openWhatsApp(service) {
  const phoneNumber = "551199999999"; // Substitua pelo número do WhatsApp
  const message = `Olá, estou interessado no serviço de ${service}. Poderia me dar mais informações?`;
  const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
  window.open(url, "_blank");
}

/*Slideshow Canais*/
document.addEventListener("DOMContentLoaded", function () {
  const slideTrack = document.querySelector(".customSlideTrack");
  const slideItems = Array.from(slideTrack.children);
  const slideWidth = slideTrack.scrollWidth; // Largura total

  // Duplica os itens para preencher espaço
  slideItems.forEach((item) => {
    let clone = item.cloneNode(true);
    slideTrack.appendChild(clone);
  });

  // Ajusta a posição quando o primeiro clone reaparece
  let movePixels = 1;
  function animateSlide() {
    movePixels -= 1; // Velocidade do movimento
    slideTrack.style.transform = `translateX(${movePixels}px)`;

    if (Math.abs(movePixels) >= slideWidth / 5) {
      movePixels = 0; // Reinicia sem "pulo"
    }

    requestAnimationFrame(animateSlide);
  }

  animateSlide();
  
});




document.addEventListener("DOMContentLoaded", () => {
  const animateElements = document.querySelectorAll(".animate");


  // Adicione a classe `start` para iniciar as animações apenas após o carregamento
  animateElements.forEach(el => el.classList.add("start"));

  const observer = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible"); // Ativa a animação
          observer.unobserve(entry.target); // Para de observar após a animação
        }
      });
    },
    {
      threshold: 0.1, // Percentual do elemento visível para ativar
    }
  );

  animateElements.forEach(element => observer.observe(element));
});
