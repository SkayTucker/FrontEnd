        /* Variáveis */
        const tagAcessbility = document.querySelectorAll('a, p, h3, h4, h5'); // seleciona tags de acessibilidade        
        
        
        /* Botões */
        var darkBtn = document.getElementById('DarkBtn');
        var zoonInBtn = document.getElementById('zoomInBtn');
        var zoonOutBtn = document.getElementById('zoomOutBtn');


        //DarkMode
        function darkMode() {
            document.body.classList.toggle("dark-mode");
            // Verifica se o body tem a classe 'dark-mode'
            if (document.body.classList.contains('dark-mode')) {
                darkBtn.innerHTML = "Light Mode";
            } else {
                darkBtn.innerHTML = "Dark Mode";
            }
            
        }

        function zoomIn() {
            let forEachBody = document.body;
            // Itera sobre cada elemento selecionado
            tagAcessbility.forEach(function(tagAcessbility) {
                // Obtém o tamanho da fonte atual
                var currentSize = window.getComputedStyle(tagAcessbility, null).getPropertyValue('font-size');
                // Remove o "px" e converte para um número
                var newSize = parseFloat(currentSize) + 2; // Aumenta o tamanho da fonte em 2px

                // Define o novo tamanho de fonte
                tagAcessbility.style.fontSize = newSize + 'px';

            });
        }

        function zoomOut() {
            let forEachBody = document.body;
            // Itera sobre cada elemento selecionado
            tagAcessbility.forEach(function(tagAcessbility) {
                // Obtém o tamanho da fonte atual
                var currentSize = window.getComputedStyle(tagAcessbility, null).getPropertyValue('font-size');
                // Remove o "px" e converte para um número
                var newSize = parseFloat(currentSize) - 2; // Aumenta o tamanho da fonte em 2px

                // Define o novo tamanho de fonte
                tagAcessbility.style.fontSize = newSize + 'px';

            });   
        }


