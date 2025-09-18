document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".slider-btn");
  const content = document.getElementById("planContent");
  const planRadios = document.querySelectorAll('.plan-switch input[name="plan"]');


  // Valores dos planos por tipo
  const planValues = {
    monthly: { start: 35, premium: 59, ultra: 99, period: "/mês" }, // 10 / 20
    quarterly: { start: 189, premium: 323, ultra: 539, period: "/semestre" },
    yearly: { start: 336, premium: 574, ultra: 959, period: "/ano" }
  };

  // Atualiza preços, período e adiciona desconto após o período
  function updatePrices(type) {
    document.querySelectorAll('.hplay-card').forEach(card => {
      const planName = card.querySelector('.card-top').textContent.trim().toLowerCase();
      const priceElem = card.querySelector('.price strong');
      const periodElem = card.querySelector('.price span');

      if (!priceElem || !periodElem) return;

      let value;

      if (planName.includes('start')) {
        value = planValues[type].start;
      } else if (planName.includes('premium')) {
        value = planValues[type].premium;
      } else if (planName.includes('ultra')) {
        value = planValues[type].ultra;
      }

      // Define desconto
      let discount = "";
      if (planName.includes('premium') && (type === "quarterly" || type === "yearly")) {
        discount = " <small class='discount'>10% OFF</small>";
      } else if (planName.includes('ultra') && (type === "quarterly" || type === "yearly")) {
        discount = " <small class='discount'>20% OFF</small>";
      }

      // Atualiza valor e período
      priceElem.textContent = value;
      periodElem.innerHTML = planValues[type].period + discount;
    });
  }



  // Atualiza o estado ativo do slider
  function updateSliderActive(selectedValue) {
    const labels = document.querySelectorAll('.plan-switch label');
    labels.forEach(label => {
      const input = document.getElementById(label.getAttribute('for'));
      if (input.value === selectedValue) label.classList.add('active');
      else label.classList.remove('active');
    });
  }

  // Carrega o conteúdo dos planos via AJAX
  async function loadPlanContent(type) {
    content.innerHTML = "<p>Carregando planos...</p>";
    try {
      const response = await fetch(`planos/${type}.html`);
      if (!response.ok) throw new Error("Erro ao carregar planos");
      const html = await response.text();
      content.innerHTML = html;

      // Atualiza preços após o HTML ser carregado
      const selectedRadio = document.querySelector('.plan-switch input[name="plan"]:checked');
      if (selectedRadio) {
        updatePrices(selectedRadio.value);
        updateSliderActive(selectedRadio.value);
      }
    } catch (error) {
      content.innerHTML = `<p class="error">Falha ao carregar planos. Tente novamente.</p>`;
      console.error(error);
    }
  }

  // Inicializa com catalog
  loadPlanContent("catalog");

  // Botões que trocam o tipo de plano via fetch
  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(b => {
        b.classList.toggle("active", b === btn);
        b.setAttribute("aria-selected", b === btn ? "true" : "false");
      });

      const type = btn.getAttribute("data-type");
      loadPlanContent(type);
    });
  });

  // Radios do slider de tempo (mensal, trimestral, anual)
  planRadios.forEach(radio => {
    radio.addEventListener('change', () => {
      updatePrices(radio.value);
      updateSliderActive(radio.value);
    });
  });

  // Inicializa visual do slider
  const selectedRadio = document.querySelector('.plan-switch input[name="plan"]:checked');
  if (selectedRadio) updateSliderActive(selectedRadio.value);
});
