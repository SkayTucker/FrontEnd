const MyAjax = (() => {
    // Verifica se XMLHttpRequest está disponível
    const isSupported = () => {
        return typeof XMLHttpRequest !== 'undefined';
    };

    // Faz uma requisição GET
    const get = (url, callback, errorCallback) => {
        if (!isSupported()) {
            console.error("AJAX não suportado neste navegador.");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                callback(xhr.responseText);
            } else {
                errorCallback(`Erro: ${xhr.status} - ${xhr.statusText}`);
            }
        };

        xhr.onerror = () => {
            errorCallback("Erro de rede ou de conexão.");
        };

        xhr.send();
    };

    // Faz uma requisição POST
    const post = (url, data, callback, errorCallback) => {
        if (!isSupported()) {
            console.error("AJAX não suportado neste navegador.");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                callback(xhr.responseText);
            } else {
                errorCallback(`Erro: ${xhr.status} - ${xhr.statusText}`);
            }
        };

        xhr.onerror = () => {
            errorCallback("Erro de rede ou de conexão.");
        };

        xhr.send(JSON.stringify(data));
    };

    // Testa se o servidor está acessível
    const testConnection = (url, callback) => {
        get(
            url,
            () => callback(true),
            () => callback(false)
        );
    };

    // API pública
    return {
        isSupported,
        get,
        post,
        testConnection
    };
})();
