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



/*  *SLIDE PLANS*  */
/*  *SLIDE PLANS*  */
// Selecionando os elementos do slider
const slider = document.querySelector(".slider");
const slidesPlan = document.querySelectorAll(".slider .slide");
const sliderButtons = document.querySelectorAll(".slider-btn");
const prevPlanBtn = document.querySelector(".prevPlan");
const nextPlanBtn = document.querySelector(".nextPlan");

let currentPlanSlide = 0; // Inicializa o índice do slide atual

// Atualiza a posição dos slides e os botões ativos
function showPlanSlide(index) {
  slider.style.transform = `translateX(-${index * 100}%)`;
  slider.style.transition = "transform 0.5s ease-in-out"; // Suaviza a transição

  // Remove a classe 'active' de todos os botões e adiciona no botão correto
  sliderButtons.forEach((btn) => btn.classList.remove("active"));
  sliderButtons[index].classList.add("active");
}

// Navegação pelos botões de seleção
sliderButtons.forEach((button, index) => {
  button.addEventListener("click", () => {
    currentPlanSlide = index;
    showPlanSlide(currentPlanSlide);
  });
});

// Próximo slide
nextPlanBtn.addEventListener("click", () => {
  currentPlanSlide = (currentPlanSlide + 1) % slidesPlan.length;
  showPlanSlide(currentPlanSlide);
});

// Slide anterior
prevPlanBtn.addEventListener("click", () => {
  currentPlanSlide = (currentPlanSlide - 1 + slidesPlan.length) % slidesPlan.length;
  showPlanSlide(currentPlanSlide);
});

// Inicializa o primeiro slide visível e o botão ativo
showPlanSlide(currentPlanSlide);




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

