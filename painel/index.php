<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Login</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/acessibilidade.css">
</head>
<body>

<header>
        <div id="logo">
            <img src="../assets/img/logo.png" alt="" width="48">
        </div>
    <!-- SideBar/Navigation -->
    <div id="headerNav" class="headerNav">
        <!-- Navegador de Paginas -->
        <nav id="headerMenu">
            
            <!-- Fechar Menu Mobile -->
            <button class="closeMenu gradient-dourado" onclick="sideBar()">
                Fechar
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                </svg>                
            </button> 

            <a href="../index.html">Home</a>
            <a href="#"class="active">Painel</a>
            <a href="#">Serviços</a>
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
    
<main class="main-page">
    <section>

        <div class="painelForm" id="loginForm">       
            <!-- Formulário de Login -->
            <form action="config/logar.php" method="post">
            <h1>Login no Sistema</h1>
                <label for="user">Login</label>
                <input type="text" placeholder="Usuário" name="nome" id="user" required>
                <label for="pwd">Senha</label>
                <input type="password" name="senha" id="pwd" required>
                <br>
                
                <input type="submit" value="Acessar">
            </form>


            <a onclick="toggleForm()">Cadastre-se</a>
            <a onclick="">Esqueceu a senha?</a>

        </div>

        <div class="painelForm" id="regForm">
            <!-- Formulário de Cadastro -->
            <form action="config/cadastrar.php" method="post">
            <h1>Cadastro de Usuário</h1>    
                <label for="nome">Usuário</label>
                <input type="text" placeholder="Nome do Usuário" name="nome" id="nome" required>
                <label for="email">Email</label>
                <input type="email" placeholder="example@example.com" name="email" id="email" required>
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
                
                <br><input type="submit" value="Criar Usuário">
            </form>

            <a onclick="toggleForm()">Ja é Cadastrado? Faça Login</a>
        </div>
    </section>
</main>

<footer class="gradient-dourado">
    DragonDev Desenvolvimento Web
</footer>
   
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



