<?php
require_once '../app/config/includes.php';
verificarAutenticacao();

$user_id = $_SESSION['user_id'] ?? null;
$plate = $_GET['plate'] ?? '';

if (!$user_id || !$plate) {
    echo "Acesso inválido.";
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.id AS checkin_id, c.created_at, d.quilometragem_inicial, v.brand, v.model, v.plate 
    FROM vehicle_checkin c 
    JOIN vehicle_checkin_details d ON c.id = d.checkin_id 
    JOIN vehicles v ON v.plate = c.vehicle_plate
    WHERE c.vehicle_plate = ? AND c.user_id = ? AND c.status = 'em uso'
");
$stmt->execute([$plate, $user_id]);
$checkin = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$checkin) {
    echo "<p>Nenhum check-in ativo encontrado para esse veículo.</p>";
    exit;
}

$dataCheckin = new DateTime($checkin['created_at']);
?>


<h2 class="modal-title">Devolução do Veículo: <?= htmlspecialchars("{$checkin['brand']} {$checkin['model']} ({$checkin['plate']})") ?>
</h2>

<div role="region" aria-label="Informações do veículo">
    <section class="modal-body-inner">
        <h3 style="text-align:center;">Checkout</h3>
        <form id="solicitarForm" method="POST" action="../src/includes/painel/processar_devolucao.php"
            enctype="multipart/form-data">
            <input type="hidden" name="plate" value="<?= htmlspecialchars($checkin['plate']) ?>">
            <input type="hidden" name="checkin_id" value="<?= htmlspecialchars($checkin['checkin_id']) ?>">

            <!-- Last-Checkin -->
            <fieldset
                style="background-color:rgba(248, 189, 180, 0.77); padding: 0 20px; border: 1px solid #cce5ff; border-radius: 6px;">
                <legend style="font-weight: bold; color: #0056b3;">Last Checkin</legend>
                <p><strong>Veículo:</strong>
                    <?= htmlspecialchars("{$checkin['brand']} {$checkin['model']} ({$checkin['plate']})") ?></p>
                <p><strong>Data da Solicitação:</strong> <?= $dataCheckin->format('d/m/Y H:i') ?></p>
                <p><strong>Quilometragem Inicial:</strong> <?= number_format($checkin['quilometragem_inicial'], 0, ',', '.') ?>
                    km</p>
            </fieldset>


            <fieldset class="solicitar-veiculos-info">
                <fieldset>
                    <input type="hidden" name="plate" value="<?= htmlspecialchars($veiculo['plate']) ?>">
                    <input type="number" id="quilometragem_final" name="quilometragem_final" min="0" placeholder="Quilometragem Atual" required>
                    <select id="combustivel" name="combustivel" required>
                        <option value="" disabled selected>Combustível</option>
                        <option value="vazio">Vazio</option>
                        <option value="1/4">1/4</option>
                        <option value="1/2">1/2</option>
                        <option value="3/4">3/4</option>
                        <option value="cheio">Cheio</option>
                    </select>
                    <input type="file" name="fotos[]" id="fotos" multiple accept="image/*" required aria-describedby="file-feedback"
                        onchange="validateFiles()">
                    <small id="file-feedback" style="color:#555;">Você pode enviar até 5 fotos.</small>
                </fieldset>

                <input type="hidden" name="tipo_pasta" value="devolucao">
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