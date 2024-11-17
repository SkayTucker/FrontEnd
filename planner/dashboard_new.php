<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: painel/dashboard.php");
    exit();
}

// Conexão com o banco de dados
include 'config/dbconnect.php';

// Obtendo informações do usuário
$usuarioId = $_SESSION['usuario_id'];
$sql = "SELECT nome, email, criado_em FROM site_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se o usuário foi encontrado
if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $nomeUsuario = $usuario['nome'];
    $emailUsuario = $usuario['email'];
    $ultimoAcesso = $usuario['criado_em'];
} else {
    header("Location: ../../painel.php");
    exit();
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/style.css">
    <title>Dragon Quests</title>
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclua o ImageMapster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imagemapster/1.5.4/jquery.imagemapster.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ativando o plugin ImageMapster na imagem
            $('img[usemap]').mapster({
                fillColor: 'FFFFFF', // Cor de preenchimento ao passar o mouse
                fillOpacity: 0.5,    // Opacidade do preenchimento
                stroke: true,        // Exibir a borda ao redor da área
                strokeWidth: 1,
                strokeColor: 'FFFFFF', // Cor da borda
                isSelectable: true,  // seleção de áreas
                showToolTip: true,    // Habilitar tooltips
                toolTipContainer: '<div style="background-color: #fff; border: 1px solid #000; padding: 5px;"></div>',
                toolTipClose: ['area-mouseout']
            });

            // Função para capturar eventos de clique nas áreas do mapa
            $('area').on('click', function(event) {
                event.preventDefault();
                const areaId = $(this).attr('alt');
                $('.info').text('Você clicou na região: ' + areaId);
            });
        });
    </script>
</head>
<body class="gradient-dourado">
    <!-- HEADER/ASIDE -->
    <header class="gradient-dourado">
        <div id="logo"><img src="../assets/img/favicon.png" alt="" width="48">ragon Quests</div>  

        <nav class="navDashboard">
            <a href="#" data-page="home.php">Status</a>
            <a href="#" data-page="char.php">Personagem</a>
            <a href="#" data-page="cidade.php">Cidade</a>
            <a href="config/logout.php">Sair</a>
        </nav>

             
    <div class="statusDashboard">
            <fieldset>
                <legend>Informações do Usuário</legend>
                <div id="name"><strong>Usuário</strong>: <?php echo htmlspecialchars($nomeUsuario); ?></div>
                <div id="email"><strong>Email</strong>: <?php echo htmlspecialchars($emailUsuario); ?></div>
            </fieldset>
            
            <fieldset id="actions">
                <legend>Ações</legend>
                <a href="#" id="home">Casa</a>
                <a href="#" id="habilidades">Skills</a>
                <a href="#" id="cidade">Cidade</a>
                <a href="#" id="clans">Clan</a>
                <a href="#" id="amigos">Amigos</a>
            </fieldset>


            <fieldset id="charQuests">
                <legend>Quests/Missões</legend>
                <div id="quests">
                    <fieldset>
                        <legend>Atuais</legend>
                        <div id="questAtiva"></div>
                    </fieldset>

                    <fieldset>
                        <legend>Recomendadas</legend>
                        <div id="questRec"></div>
                    </fieldset>

                    <fieldset>
                        <legend>Importantes</legend>
                        <div class="questImportante"></div>
                    </fieldset>
                </div>
            </fieldset>

    </div>


    <footer>
        Dragon Quests <sub style="font-size: 10px; color:black;">Versão Beta</sub>
    </footer>

    </header>  
    <!-- <section>
        <form action="includes/criar_personagem.php" method="POST">
            <fieldset>
                <legend>Criar Personagem</legend>
                <label for="genero">Escolha o gênero do personagem:</label><br>
                <input type="radio" id="male" name="genero" value="male">
                <label for="male">Masculino</label><br>
                <input type="radio" id="female" name="genero" value="female">
                <label for="female">Feminino</label><br><br>
                <input type="submit" value="Criar Personagem">
            </fieldset>            
        </form>
    </section> -->
<main id="game-content">   
</main>

<script>
    // Função para carregar o conteúdo da página clicada
    function carregarConteudoJogo(pagina) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "pages/" + pagina, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("game-content").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Função para adicionar o evento de clique na navegação
    function configurarNavegacao() {
        var links = document.querySelectorAll('.navDashboard a[data-page]');
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault(); // Impede o comportamento padrão de redirecionamento
                var pagina = this.getAttribute('data-page');
                carregarConteudoJogo(pagina); // Carrega a página clicada
            });
        });
    }

    // Carrega o conteúdo inicial e configura a navegação ao carregar a página
    window.onload = function() {
        carregarConteudoJogo("home.php"); // Página inicial padrão
        configurarNavegacao(); // Configura os cliques da navegação
    };
</script>


</body>
</html>
