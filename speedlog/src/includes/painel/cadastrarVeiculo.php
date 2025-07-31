<!-- Título usado para o header do modal -->
<h2 class="modal-title">Cadastrar Veículo</h2>

<form id="form-cadastrar-veiculo" method="POST" action="/speedlog/src/includes/painel/salvarVeiculo.php" enctype="multipart/form-data">
    <section class="modal-body-inner">
        <fieldset>
            <legend>Informações do Veículo</legend>
            <input type="text" name="plate" required maxlength="10" pattern="[A-Z0-9\-]+" placeholder="ABC-1234" autofocus>
            <input type="text" name="brand" required maxlength="50" placeholder="Fabricante">
            <input type="text" name="model" required maxlength="50" placeholder="Modelo">
            <input type="number" name="year" required min="1900" max="9999" placeholder="Ano">
            <label>Disponibilidade:
                <select name="availability" required>
                    <option value="">Selecione...</option>
                    <option value="disponível">Disponível</option>
                    <option value="indisponível">Indisponível</option>
                </select>
            </label>
            <label>

            </label>
        </fieldset>

        <fieldset>
            <legend>Imagem do Veículo</legend>

            <label>
                Selecionar imagem existente:
                <select name="imagem_existente" id="imagem_existente">
                    <option value="">Selecionar imagem existente...</option>
                    <?php
                    $imagens = ['carro.jpg', 'celta.jpg', 'corolla.jpg', 'gol.jpg', 'lexus.jpg', 'social.jpg', 'teste.jpg', 'teste2.jpg', 'uno.jpg'];
                    foreach ($imagens as $img) {
                        echo "<option value=\"$img\">$img</option>";
                    }
                    ?>
                </select>
            </label>

            <label>
                Ou envie uma imagem nova (.JPG ou .JPEG apenas):
                <input type="file" name="nova_imagem" accept=".jpg,.jpeg,image/jpeg" id="nova_imagem">
            </label>
        </fieldset>
    </section>

    <footer class="modal-footer">
        <button type="submit" class="salvar-veiculo">Salvar Veículo</button>
        <button type="button" class="close-modal">Cancelar</button>
    </footer>
</form>

<script>
    document.getElementById('form-cadastrar-veiculo').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const fileInput = form.querySelector('#nova_imagem');
        const file = fileInput.files[0];

        // Validação simples do arquivo
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg'].includes(ext)) {
                alert('A imagem deve estar no formato JPG ou JPEG.');
                return;
            }
        }

        const data = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: data
            });

            const text = await response.text();

            // Garante que a resposta seja JSON válida
            let result;
            try {
                result = JSON.parse(text);
            } catch (parseError) {
                console.error('Resposta não é JSON:', text);
                throw new Error('Resposta inválida do servidor.');
            }

            alert(result.message || 'Operação concluída.');

            if (result.status === 'success') {
                window.location.reload();
            }
        } catch (error) {
            console.error(error);
            alert('Erro ao comunicar com o servidor.');
        }
    });

    // Botão cancelar fecha o modal
    document.querySelector('.close-modal')?.addEventListener('click', () => {
        window.closeModal?.();
    });
</script>