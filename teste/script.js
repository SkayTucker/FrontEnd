const areas = document.querySelectorAll('area');

areas.forEach(area => {
    area.addEventListener('click', (event) => {
        event.preventDefault();  // Prevenir o comportamento padrão de navegação

        const url = area.getAttribute('href');  // Pega o link da área clicada

        // Aqui você pode carregar dinamicamente o conteúdo da nova área
        document.querySelector('main').innerHTML = `<object type="text/html" data="${url}" ></object>`;
    });
});
