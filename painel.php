
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragon Quests</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>
<body class="pagePainel">

    <!-- HEADER/NAVBAR -->
    <header id="header">       
        <!-- SideBar/Navigation -->
        <div id="headerNav" class="headerNav">
    
            <div id="logo">
                <img style="filter: drop-shadow(0px 0px 2px rgb(255, 208, 0));" src="assets/img/favicon.png" alt="" width="48">
            </div>
    
            <!-- Navegador de Paginas -->
            <nav id="headerMenu" class="headerNav">
                
                <!-- Fechar Menu Mobile -->
                <button class="closeMenu gradient-dourado" onclick="sideBar()">
                    Fechar
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>                
                </button> 

                <a href="index.html">Home</a>
                <a class="active">Planner</a>
                <a href="jogo.html">Lineage</a>
                <a href="world.html">Lineage World</a>
                <a href="#">Contato</a>
    
    
                <!-- Abrir Menu Acessibilidade -->
                <button class="openAcess" onclick="showAcess()">
                    ♿
                </button>
                <!-- Menu Acessibilidade -->
                    <!-- <div class="acessibilidade">
                        <p>teste</p>
                        <ul>
                            <li class="zoomInBtn" onclick="zoomIn()"></li>
                            <li class="zoomOutBtn" onclick="zoomOut()"></li>
                            <li id="DarkBtn" onclick="darkMode()"></li>
                        </ul>  
                    </div> -->              
            </nav>
          
        </div>  
    
                <!-- Abrir Menu Mobile -->
            <button class="openMenu gradient-dourado" id="openMenu" onclick="sideBar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
            </button>    
    </header>
          
<main onclick="sideBarClose()">
    <!-- LOGIN -->
    <section>

        <div class="painelForm" id="loginForm">       
            <form action="jogo/painel/config/logar.php" method="post">
                <fieldset>
                     <legend>Painel</legend>    
                        <label for="user">Login</label>
                        <input type="text" placeholder="Usuário" name="nome" id="user" required>
                        <label for="pwd">Senha</label>
                        <input type="password" name="senha" id="pwd" required>
                        <br>
                        <input class="btnForm" type="submit" value="Acessar" >
                        <a onclick="toggleForm()"> Cadastre-se</a>
                </fieldset>   
            </form>

            </div>
         
        <!-- CADASTRO -->
        <div class="painelForm" id="regForm">
            <form action="jogo/painel/config/cadastrar.php" method="post"> 
                <fieldset>
                    <legend>Cadastro</legend>                   
                <label for="nome">Usuário</label>
                <input type="text" placeholder="Nome do Usuário" name="nome" id="nome" required>
                <label for="email">Email</label>
                <input type="email" placeholder="example@example.com" name="email" id="email" required>
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
                <br>
                <input type="submit" value="Criar Usuário" class="btnForm">
                <a onclick="toggleForm()">Ja é Cadastrado? Faça Login</a>
            </fieldset>   
        </form>


        </div>
    </section>
</main>

    <script>
        var loginForm = document.getElementById("loginForm");
        var regForm = document.getElementById("regForm");
        var showLoginForm = true;  // Começa mostrando o login

        function toggleForm() {
            showLoginForm = !showLoginForm;

            if (showLoginForm) {
                loginForm.style.display = "block";
                regForm.style.display = "none";
            } else {
                loginForm.style.display = "none";
                regForm.style.display = "block";
            }
        }

        // Definindo o estado inicial
        loginForm.style.display = "block";
        regForm.style.display = "none";


    </script>

    <script src="../assets/js/script.js"></script>

    <script src="../assets/js/Acessibilidade.js"></script>
</body>
</html>



