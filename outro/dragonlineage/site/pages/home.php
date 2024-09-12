<!-- <div class="container d-flex">
    <aside class="server-features left">
        <div class="card" style="width: 18rem;">
            <img src="./assets/img/header.jpg" class="card-img-top" alt="Imagem 1">
            <div class="card-body">
                <h5 class="card-title">Raid Bosses</h5>
                <p class="card-text">Desafios Inimagináveis.</p>
                <a href="#" class="btn btn-primary">Acessar</a>
            </div>
        </div>
    </aside> -->

    <div class="container" style="margin:10px 0;">
    
        <h2>Bem-vindo ao Lineage II PvP Full!</h2>
        <p>Seja parte da aventura. Crie sua conta agora e junte-se à batalha!</p>

        <div class="formulario">
                <!-- Formulário de Registro -->
                <form id="registerForm" method="post" action="./config/includes/processa_registro.php">
                    <h3>Crie Sua Conta!</h3>
                    <label for="login">Login:</label>
                    <input type="text" name="login" required><br>

                    <label for="password">Senha:</label>
                    <input type="password" name="password" maxlength="45" required>

                    <label for="confirm_password">Confirmar Senha:</label>
                    <input type="password" name="confirm_password" maxlength="45" required><br>

                    <input type="submit" class="confirm corPrincipal" value="Registrar">
                </form>
        </div>

    </div>




    <!-- <aside class="server-features right">
        <div class="card" style="width: 15rem;">
            <img src="./assets/img/header.jpg" class="card-img-top" alt="Imagem 2">
            <div class="card-body">
                <h5 class="card-title">Seja VIP</h5>
                <p class="card-text">Conheça os Benefícios de VIP e Donate! afinal, qual a diferença?</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    </aside> -->
</div>
