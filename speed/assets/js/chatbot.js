// Chatbot funcionalidade
const chatbotButton = document.getElementById("chatbotButton");
const chatbot = document.getElementById("chatbot");
const closeChatbot = document.getElementById("closeChatbot");

chatbotButton.addEventListener("click", () => {
  chatbot.style.display = "block";
});

closeChatbot.addEventListener("click", () => {
  chatbot.style.display = "none";
});

// Simula envio de mensagem no chatbot
document.getElementById("sendChat").addEventListener("click", () => {
  const chatInput = document.getElementById("chatInput");
  if (chatInput.value.trim() !== "") {
    alert(`Mensagem enviada: ${chatInput.value}`);
    chatInput.value = "";
  }
});
