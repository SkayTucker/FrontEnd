<style>
    .container {
        font-family: Arial, sans-serif;
            background-color: black; color: white;      
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        
    }
</style>

<div class="container">
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

