<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../painel.php");
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageMapster</title>
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclua o ImageMapster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imagemapster/1.5.4/jquery.imagemapster.min.js"></script>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
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
<body>
   <!-- HEADER DA PÁGINA -->
   <header id="header">       
    <!-- SideBar/Navigation -->
    <div id="headerNav" class="headerNav">
        <!-- Informações de Login -->
        <nav id="headerMenu" class="headerNav">
            <!-- Fechar Menu Mobile -->
            <button class="closeMenu gradient-dourado" onclick="sideBar()">
                Fechar
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                </svg>                
            </button> 

            <!-- INFORMAÇÔES HEADER DA PÀGINA -->
            <fieldset style="color:white; padding: 0 10px; margin: 0 10px;">
                <legend>Informações do Usuário</legend>
                <div id="name"><strong>Usuário</strong>: <?php echo htmlspecialchars($nomeUsuario); ?></div>
                <div id="email"><strong>Email</strong>: <?php echo htmlspecialchars($emailUsuario); ?></div>
            </fieldset>

            <a href="#">Configurações</a>
            <a href="#">Minha Conta</a>
        </nav>
      
    </div>  

            <!-- Abrir Menu Mobile -->
        <button class="openMenu gradient-dourado" id="openMenu" onclick="sideBar()">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
            </svg>
        </button>    
    </header>
    
<main>

   <!-- HEADER DO JOGO -->
    <header class="gradient-dourado">
        <div id="logo"><img src="../../assets/img/favicon.png" alt="" width="48">ragon Quests</div>  

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

    <div id="game-content">
        <img  src="../../assets/img/mapa.png" alt="Mapa de Exemplo" usemap="#mapaExemplo"/>

        <!-- Definindo as áreas da imagem -->
        <map name="mapaExemplo">
            <!-- coords = left,top,right,bottom -->
            <!-- coords = left,bottom,right,top -->
            <area shape="rect" coords="310,60,150,140" href="#" alt="Templo" />
            <area shape="rect" coords="500,120,330,160" href="#" alt="Loja de Armas" />
            <area shape="rect" coords="430,175,370,240" href="#" alt="Banco" />
            <area shape="rect" coords="5,240,110,200" href="#" alt="Humanos" />
            <area shape="rect" coords="35,280,140,240" href="#" alt="Orcs" />
            <area shape="rect" coords="40,285,130,330" href="#" alt="Elfos" />
            <area shape="rect" coords="170,400,210,340" href="#" alt="Dark Elfos" />
            <area shape="rect" coords="260,400,220,340" href="#" alt="Anões" />
            <area shape="rect" coords="360,390,265,340" href="#" alt="Loja Mágica" />
            <area shape="rect" coords="330,310,250,260" href="#" alt="Gatekeeper" />
        </map>

        <!-- Informação sobre a área clicada -->
        <div class="info">Clique em uma região do mapa.</div>
    </div>
</main>

</body>
</html>
