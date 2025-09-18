document.addEventListener("DOMContentLoaded", function () {
  const chatbotButton = document.getElementById("chatbotButton");
  const chatbot = document.getElementById("chatbot");
  const closeButton = document.getElementById("closeChatbot");

  if (chatbotButton && chatbot && closeButton) {
    chatbotButton.addEventListener("click", () => {
      chatbot.style.display = chatbot.style.display === "block" ? "none" : "block";
    });

    closeButton.addEventListener("click", () => {
      chatbot.style.display = "none";
    });
  }
});

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
