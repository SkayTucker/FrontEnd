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

    <header class="gradient-dourado">
        <nav>
            <div class="logo">
                <a href="../index.html" style="display:flex; flex-direction: column; text-decoration: none; color: black;">
                    <img src="../assets/img/logo.png" alt="">< Voltar</a></div>

            <h1 style="text-align: center;">Painel do Usuário</h1>
        
            <ul>
                    <li class="zoomInBtn" onclick="zoomIn()"></li>
                    <li class="zoomOutBtn" onclick="zoomOut()"></li>
                    <li id="DarkBtn" onclick="darkMode()"></li>
            </ul>
        </nav>           
    </header>

   <section style="flex-direction:column;">

    <div class="login">
        <h1>Painel do Usuário</h1>
        <!-- Formulário de Login -->
        <form action="login.php" method="post">
            <label for="user">Usuário (Email)</label>
            <input type="email" name="email" id="user" required>
            <br><br>
            
            <label for="pwd">Senha</label>
            <input type="password" name="senha" id="pwd" required>
            <br><br>
            
            <input type="submit" value="Logar">
        </form>
    </div>

    <div class="register">
        <h1>Cadastro de Usuário</h1>    
        <!-- Formulário de Cadastro -->
        <form action="cadastrar.php" method="post">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
            <br><br>
            
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <br><br>
            
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>
            <br><br>
            
            <input type="submit" value="Cadastrar">
        </form>
    </div>

    </section>


    <script src="../assets/js/Acessibilidade.js"></script>
</body>
</html>



