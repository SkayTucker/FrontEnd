document.getElementById("whatsapp-support").addEventListener("click", () => {
    window.open("https://wa.me/5511999999999?text=Olá,%20preciso%20de%20suporte%20técnico!", "_blank");
  });
  
  document.getElementById("request-visit").addEventListener("click", () => {
    alert("Nossa equipe entrará em contato para agendar a visita técnica.");
  });
  
  function openWhatsApp(service) {
    const phoneNumber = "551199999999";
    const message = `Olá, estou interessado no serviço de ${service}. Poderia me dar mais informações?`;
    const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(url, "_blank");
  }
  