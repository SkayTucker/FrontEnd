<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Personagem</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
        }
        .status-bar {
            background-color: #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
            height: 25px;
            position: relative;
        }
        .status-fill {
            background-color: #4CAF50;
            height: 100%;
            width: 0;
            border-radius: 10px;
            position: absolute;
            top: 0;
            left: 0;
            transition: width 0.5s;
        }
        .attribute-list, .skills-list, .equipment-list {
            list-style: none;
            padding: 0;
        }
        .attribute-list li, .skills-list li, .equipment-list li {
            margin: 10px 0;
            font-size: 18px;
        }
        .column {
            display: flex;
            justify-content: space-between;
        }
        .column div {
            flex: 1;
            margin: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Status do Personagem</h1>

    <h2>Atributos:</h2>
    <ul class="attribute-list">
        <li>Nome: <span id="characterName">Carregando...</span></li>
        <li>Nível: <span id="characterLevel">Carregando...</span></li>
        <li>Classe: <span id="characterClass">Carregando...</span></li>
        <li>Força: <span id="characterStrength">Carregando...</span></li>
        <li>Agilidade: <span id="characterAgility">Carregando...</span></li>
        <li>Inteligência: <span id="characterIntelligence">Carregando...</span></li>
        <li>Vitalidade: <span id="characterVitality">Carregando...</span></li>
    </ul>

    <h2>Status:</h2>
    <label>Vida:</label>
    <div class="status-bar">
        <div class="status-fill" id="healthBar"></div>
    </div>
    <label>Mana:</label>
    <div class="status-bar">
        <div class="status-fill" id="manaBar"></div>
    </div>
    <label>Experiência:</label>
    <div class="status-bar">
        <div class="status-fill" id="xpBar"></div>
    </div>

    <div class="column">
        <div>
            <h2>Habilidades:</h2>
            <ul class="skills-list">
                <li id="skill1">Carregando...</li>
                <li id="skill2">Carregando...</li>
                <li id="skill3">Carregando...</li>
            </ul>
        </div>
        <div>
            <h2>Equipamentos:</h2>
            <ul class="equipment-list">
                <li id="equipment1">Carregando...</li>
                <li id="equipment2">Carregando...</li>
                <li id="equipment3">Carregando...</li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Simulação de dados do personagem
    const personagem = {
        nome: "Artemis",
        nivel: 10,
        classe: "Guerreiro",
        forca: 20,
        agilidade: 15,
        inteligencia: 10,
        vitalidade: 25,
        status: {
            vida: 85,      // valor em porcentagem
            mana: 50,      // valor em porcentagem
            experiencia: 75 // valor em porcentagem
        },
        habilidades: ["Corte Rápido", "Defesa de Ferro", "Ataque Devastador"],
        equipamentos: ["Espada Lendária", "Escudo de Ouro", "Armadura de Dragão"]
    };

    // Função para carregar e exibir os dados do personagem
    function carregarPersonagem() {
        document.getElementById('characterName').innerText = personagem.nome;
        document.getElementById('characterLevel').innerText = personagem.nivel;
        document.getElementById('characterClass').innerText = personagem.classe;
        document.getElementById('characterStrength').innerText = personagem.forca;
        document.getElementById('characterAgility').innerText = personagem.agilidade;
        document.getElementById('characterIntelligence').innerText = personagem.inteligencia;
        document.getElementById('characterVitality').innerText = personagem.vitalidade;

        // Atualizando status (vida, mana, experiência)
        document.getElementById('healthBar').style.width = personagem.status.vida + '%';
        document.getElementById('manaBar').style.width = personagem.status.mana + '%';
        document.getElementById('xpBar').style.width = personagem.status.experiencia + '%';

        // Carregar Habilidades
        document.getElementById('skill1').innerText = personagem.habilidades[0];
        document.getElementById('skill2').innerText = personagem.habilidades[1];
        document.getElementById('skill3').innerText = personagem.habilidades[2];

        // Carregar Equipamentos
        document.getElementById('equipment1').innerText = personagem.equipamentos[0];
        document.getElementById('equipment2').innerText = personagem.equipamentos[1];
        document.getElementById('equipment3').innerText = personagem.equipamentos[2];
    }

    // Carregar os dados do personagem ao carregar a página
    window.onload = carregarPersonagem;
</script>

</body>
</html>
