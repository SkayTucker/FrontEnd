<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Parea Tech</title>
</head>

<body>
    <div class="container">
        <h1>Bem-vindo ao Parea Tech</h1>

        <section id="cadastro-produto">
            <h2>Cadastro de Produtos</h2>
            <form action="db/dbQueries.php?action=insert_product" method="POST">
                <label for="product_name">Nome do Produto:</label>
                <input type="text" id="product_name" name="product_name" required>

                <label for="product_price">Preço:</label>
                <input type="number" id="product_price" name="product_price" step="0.01" required>

                <label for="product_category">Categoria:</label>
                <input type="text" id="product_category" name="product_category" required>

                <button type="submit">Cadastrar Produto</button>
            </form>
        </section>




        <section id="cadastro-usuario">
            <h2>Cadastro de Usuários</h2>
            <form action="../dbQueries.php?action=insert_user" method="POST">
                <label>Login:</label>
                <input type="text" name="login" required>

                <label>Senha:</label>
                <input type="password" name="password" required>

                <label for="email">Categoria:</label>
                <select name="Categoria" id="">
                    <option value="">Administrador</option>
                    <option value="">Gerente</option>
                    <option value="">Operador</option>
                </select>


                <button type="submit">Cadastrar Usuário</button>
            </form>
        </section>



    </div>
</body>

</html>