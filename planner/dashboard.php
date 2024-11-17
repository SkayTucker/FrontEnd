<?php
session_start(); // Verifica se a sessão do usuário está ativa
include 'config/dbconnect.php'; // Conecta ao banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login caso não esteja logado
    exit();
}

// Recupera o nome do usuário logado
$user_id = $_SESSION['usuario_id'];
$sql_usuario = "SELECT nome FROM site_users WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $user_id);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$nome_usuario = $usuario['nome'];

$stmt_usuario->close();

// Consultar projetos, atividades e eventos
$sql_projetos = "SELECT * FROM projects WHERE status = 'em andamento'";
$sql_atividades = "SELECT * FROM activities WHERE status = 'pendente' ORDER BY due_date ASC";
$sql_eventos = "SELECT * FROM calendar_events ORDER BY start_time ASC";
$sql_notificacoes = "SELECT * FROM notifications WHERE status = 'não lida' ORDER BY date DESC";

$stmt_projetos = $conn->query($sql_projetos);
$stmt_atividades = $conn->query($sql_atividades);
$stmt_eventos = $conn->query($sql_eventos);
$stmt_notificacoes = $conn->query($sql_notificacoes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dragon Planner</title>
    <link rel="stylesheet" href="assets/style.css"> <!-- Link para o arquivo de estilo CSS -->
</head>
<body>

    <div class="navbar">
        <h1>Dragon Planner - Dashboard</h1>
        <div class="user-info">
            <span>Bem-vindo, <?php echo $nome_usuario; ?></span>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <div class="content">
        <div class="section">
            <h2>Projetos em Andamento</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Data de Início</th>
                    <th>Data de Término</th>
                </tr>
                <?php while ($project = $stmt_projetos->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $project['project_id']; ?></td>
                        <td><?php echo $project['name']; ?></td>
                        <td><?php echo $project['description']; ?></td>
                        <td><?php echo $project['status']; ?></td>
                        <td><?php echo $project['start_date']; ?></td>
                        <td><?php echo $project['end_date']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="section">
            <h2>Atividades Pendentes</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Data de Vencimento</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                </tr>
                <?php while ($atividade = $stmt_atividades->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $atividade['activity_id']; ?></td>
                        <td><?php echo $atividade['title']; ?></td>
                        <td><?php echo $atividade['due_date']; ?></td>
                        <td><?php echo $atividade['priority']; ?></td>
                        <td><?php echo $atividade['status']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="section">
            <h2>Eventos no Calendário</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Data de Início</th>
                    <th>Data de Término</th>
                    <th>Projeto</th>
                </tr>
                <?php while ($evento = $stmt_eventos->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $evento['event_id']; ?></td>
                        <td><?php echo $evento['title']; ?></td>
                        <td><?php echo $evento['start_time']; ?></td>
                        <td><?php echo $evento['end_time']; ?></td>
                        <td><?php echo $evento['project_id'] ? "Sim" : "Não"; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="section">
            <h2>Notificações</h2>
            <ul>
                <?php while ($notificacao = $stmt_notificacoes->fetch_assoc()) { ?>
                    <li><?php echo $notificacao['content']; ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>

</body>
</html>

<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>
