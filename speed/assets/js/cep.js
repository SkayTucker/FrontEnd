document.getElementById('cepForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita o reload da página
    const cep = document.getElementById('cepInput').value.trim();
    if (cep) {
      const message = `Olá, gostaria de consultar a disponibilidade do CEP: ${cep}`;
      const whatsappLink = `https://wa.me/558186074648?text=${encodeURIComponent(message)}`;
      window.open(whatsappLink, '_blank');
    } else {
      alert('Por favor, insira um CEP válido!');
    }
  });
  