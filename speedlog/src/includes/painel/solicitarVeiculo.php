<?php
require_once '../app/config/includes.php';
verificarAutenticacao();

$plate = $_GET['plate'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE plate = ?");
$stmt->execute([$plate]);
$veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$veiculo) {
    echo "<p>Veículo não encontrado.</p>";
    exit;
}
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="checkin-animation success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="checkin-animation error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<h2 class="modal-title">
    Solicitação do Veículo: <?= htmlspecialchars($veiculo['brand']) ?> <?= htmlspecialchars($veiculo['model']) ?> (<?= htmlspecialchars($veiculo['plate']) ?>)
</h2>

<div role="region" aria-label="Informações do veículo">

    <section class="modal-body-inner">
        <h3 style="text-align:center;">Checkin</h3>
        <form id="solicitarForm" enctype="multipart/form-data" method="POST" action="../src/includes/painel/processar_solicitacao.php" novalidate>

            <fieldset class="solicitar-veiculos-info">
                <fieldset>

                    <input type="hidden" name="plate" value="<?= htmlspecialchars($veiculo['plate']) ?>">
                    <input type="number" id="km_atual" name="km_atual" min="0" placeholder="KM Atual" required>
                    <select id="combustivel" name="combustivel" required>
                        <option value="" disabled selected>Combust.</option>
                        <option value="vazio">Vazio</option>
                        <option value="1/4">1/4</option>
                        <option value="1/2">1/2</option>
                        <option value="3/4">3/4</option>
                        <option value="cheio">Cheio</option>
                    </select>
                    <input type="file" name="fotos[]" id="fotos" multiple accept="image/*" required aria-describedby="file-feedback" onchange="validateFiles()">
                    <small id="file-feedback" style="color:#555;">Você pode enviar até 5 fotos.</small>
                </fieldset>
                <fieldset class="checklist-content">
                    <label class="marcarTudo"><input type="checkbox" id="marcarTudoChecklist"> Marcar Tudo</label>
                    <div class="checklist-items">
                        <fieldset>
                            <label><input type="checkbox" name="checklist[]" value="freios">Freios</label>
                            <label><input type="checkbox" name="checklist[]" value="pneus">Pneus</label>
                            <label><input type="checkbox" name="checklist[]" value="luzes">Luzes</label>
                            <label><input type="checkbox" name="checklist[]" value="óleo">Óleo</label>
                            <label><input type="checkbox" name="checklist[]" value="lataria">Lataria</label>
                            <label><input type="checkbox" name="checklist[]" value="combustivel">Gasolina</label>
                        </fieldset>
                    </div>
                </fieldset>
            </fieldset>
            <fieldset class="solicitar-veiculos-geo">
                <p id="geo-status">📍 Clique no botão para permitir o acesso à localização.</p>
                <p id="endereco" style="font-weight: bold;"></p>
                <div id="map-container">
                    <iframe id="map-frame" width="100%" height="100" style="border:0" loading="lazy" allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade" src="">
                    </iframe>
                </div>
                <div id="endereco-box"
                    style="display:none; font-weight: bold; margin-top: 10px; padding: 8px; border: 1px solid #ccc; border-radius: 6px; background-color: #f9f9f9;">
                </div>

                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>
                <input type="hidden" name="endereco" id="enderecoInput" required>


                <input type="hidden" name="tipo_pasta" value="solicitacao">

                <button type="button" id="btn-permitir-localizacao">📍 Permitir localização</button>
                <button type="submit" id="checkin-btn" disabled>📍 Confirmar e Finalizar Solicitação</button>

            </fieldset>
        </form>
    </section>
</div>
<script>
    /* ================= modal-inner.js ================= */
    /*  Este arquivo define initModalInnerJS no escopo global */
    /*  e funciona sem usar módulos ES6 (export/import)        */

    (function(global) {
        global.initModalInnerJS = function() {
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
                                    const {
                                        road,
                                        suburb,
                                        city,
                                        state
                                    } = data.address;
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
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }
    })(window);
</script>