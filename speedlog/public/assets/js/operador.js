/* ================= modal-inner.js ================= */
/*  Este arquivo define initModalInnerJS no escopo global */
/*  e funciona sem usar módulos ES6 (export/import)        */

(function (global) {
  global.initModalInnerJS = function () {
    const modalBody = document.getElementById('modal-body');
    if (!modalBody) return;

    // Remove event listeners antigos
    modalBody.querySelectorAll('[data-modal-template-url]').forEach(btn => {
      btn.replaceWith(btn.cloneNode(true));
    });

    // Reatribui os eventos
    modalBody.querySelectorAll('[data-modal-template-url]').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const url = btn.getAttribute('data-modal-template-url');
        const title = btn.getAttribute('data-modal-title');
        if (global.openModal) {
          global.openModal(url, title);
        }
      });
    });

    // Inicializa funcionalidades específicas
    initSolicitacaoVeiculoJS();
  };

  // Função auxiliar do sistema de solicitação de veículo
  function initSolicitacaoVeiculoJS() {
    const modalBody = document.getElementById('modal-body');
    if (!modalBody) return;

    const form = modalBody.querySelector('#solicitarForm');
    if (!form) return;

    const marcarTudo = form.querySelector('#marcarTudoChecklist');
    const checkboxes = form.querySelectorAll('input[name="checklist[]"]');

    marcarTudo?.addEventListener('change', () => {
      checkboxes.forEach(cb => cb.checked = marcarTudo.checked);
    });

    const inputFotos = form.querySelector('input[type="file"][name="fotos[]"]');
    inputFotos?.addEventListener('change', () => {
      if (inputFotos.files.length > 5) {
        alert("Você só pode enviar até 5 fotos.");
        inputFotos.value = "";
      }
    });

    const geoButton = form.querySelector('#btn-permitir-localizacao');
    const checkinBtn = form.querySelector('#checkin-btn');
    const latitudeInput = form.querySelector('#latitude');
    const longitudeInput = form.querySelector('#longitude');
    const enderecoInput = form.querySelector('#enderecoInput');
    const geoStatus = form.querySelector('#geo-status');
    const enderecoText = form.querySelector('#endereco');

    geoButton?.addEventListener('click', () => {
      geoButton.disabled = true;
      checkinBtn.disabled = true;
      geoStatus.textContent = "🔄 Buscando localização...";
      enderecoText.textContent = "";
      enderecoInput.value = "";

      navigator.geolocation.getCurrentPosition(
        position => {
          const lat = position.coords.latitude.toFixed(6);
          const lon = position.coords.longitude.toFixed(6);

          latitudeInput.value = lat;
          longitudeInput.value = lon;

          fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(resp => resp.json())
            .then(data => {
              let endereco = "Endereço não encontrado.";
              if (data.address) {
                const { road, suburb, city, state } = data.address;
                endereco = [road, suburb, city, state].filter(Boolean).join(", ");
              } else if (data.display_name) {
                endereco = data.display_name.split(",").slice(0, 3).join(", ");
              }

              enderecoInput.value = endereco;
              enderecoText.textContent = "📍 " + endereco;
              geoStatus.textContent = "✅ Localização confirmada!";
              checkinBtn.disabled = false;

              const mapContainer = form.querySelector('#map-container');
              const mapFrame = form.querySelector('#map-frame');
              const enderecoBox = form.querySelector('#endereco-box');

              const latNum = parseFloat(lat);
              const lonNum = parseFloat(lon);
              const delta = 0.005;
              mapFrame.src = `https://www.openstreetmap.org/export/embed.html?bbox=${(lonNum - delta)}%2C${(latNum - delta)}%2C${(lonNum + delta)}%2C${(latNum + delta)}&layer=mapnik&marker=${latNum}%2C${lonNum}`;

              mapContainer.style.display = 'block';
              enderecoBox.style.display = 'block';
              enderecoBox.textContent = endereco;

              geoButton.disabled = true;
            });
        },
        error => {
          let msg = "⚠️ Localização indisponível.";
          switch (error.code) {
            case error.PERMISSION_DENIED:
              msg = "🚫 Permissão de localização negada.";
              break;
            case error.POSITION_UNAVAILABLE:
              msg = "📡 Sinal de localização indisponível.";
              break;
            case error.TIMEOUT:
              msg = "⏱️ Tempo esgotado para obter localização.";
              break;
          }
          geoStatus.textContent = msg;
          geoButton.disabled = false;
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        }
      );
    });
  }
})(window);
