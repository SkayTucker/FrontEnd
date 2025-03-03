gsap.registerPlugin(TextPlugin)
  
gsap.to("#textHome", {duration: 4, text: "Desenvolvimento de Sistemas", delay: 1, repeat:3, repeatDelay:4});


   // Seleciona todas as barras de progresso
    const progressBars = document.querySelectorAll(".progress");
  
    progressBars.forEach((bar) => {
      // Obtém o valor do atributo "data-progress"
      const progressValue = bar.getAttribute("data-progress");
  
      // Define a largura da barra de progresso
      setTimeout(() => {
        bar.style.width = `${progressValue}%`;
      }, 300); // Adiciona um pequeno delay para o efeito
    });
  
  