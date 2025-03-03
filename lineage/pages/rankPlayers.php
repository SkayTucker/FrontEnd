<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rank de Players</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .rankPlayers { width: 300px; margin: 0 auto; text-align: center; }
        nav ul { list-style: none; padding: 0; }
        nav ul li { display: inline; margin: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background-color: #ddd; }
    </style>
</head>
<body>

<div class="rankPlayers">
    <h1>Rank de Players</h1>
    <nav>
        <ul>
            <li><a href="#" class="rank-link" data-rank="pvp">PvP</a></li>
            <li><a href="#" class="rank-link" data-rank="pk">PK</a></li>
        </ul>
    </nav>
    <div id="rankTable">
        <?php include '../config/rank_data.php'; // Carrega PvP por padrão ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".rank-link");
    const rankTable = document.getElementById("rankTable");

    links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const rankType = this.getAttribute("data-rank");

            fetch(`../config/rank_data.php?rank=${rankType}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    console.log("Resposta do servidor:", data); // Debug
                    rankTable.innerHTML = data;
                })
                .catch(error => console.error("Erro ao carregar ranking:", error));
        });
    });
});

</script>

</body>
</html>
