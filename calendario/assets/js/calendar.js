
document.addEventListener("DOMContentLoaded", function () {
    const calendarContainer = document.getElementById("calendar-container");
    const mesesDisplay = document.querySelector(".mesesDisplay");
    const outrosMeses = document.querySelector(".outros-meses");

    const months = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];

    const currentDate = new Date();
    const currentMonth = currentDate.getMonth(); // Mês atual
    const currentYear = currentDate.getFullYear(); // Ano atual

    const previousMonth = (currentMonth - 1 + 12) % 12; // Mês passado (circular)
    const nextMonth = (currentMonth + 1) % 12; // Mês próximo (circular)

    // Exibindo os meses Passado, Atual e Próximo
    const monthsToDisplay = [previousMonth, currentMonth, nextMonth];
    
    monthsToDisplay.forEach(i => {
        let monthDiv = document.createElement("div");
        monthDiv.classList.add("month");

        // Estilo para o mês atual e próximo mês
        if (i === currentMonth) {
            monthDiv.classList.add("current-month");
        } else if (i === nextMonth) {
            monthDiv.classList.add("next-month");
        } else {
            monthDiv.classList.add("other-month");
        }

        let monthTitle = document.createElement("h2");
        monthTitle.innerText = months[i];
        monthDiv.appendChild(monthTitle);

        let daysDiv = document.createElement("div");
        daysDiv.classList.add("days");

        // Adicionando os dias da semana
        let weekDays = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"];
        let weekDiv = document.createElement("div");
        weekDiv.classList.add("week");
        weekDays.forEach(day => {
            let dayDiv = document.createElement("div");
            dayDiv.classList.add("day");
            dayDiv.innerText = day;
            weekDiv.appendChild(dayDiv);
        });
        daysDiv.appendChild(weekDiv);

        // Obtendo os dias do mês
        let daysInMonth = new Date(currentYear, i + 1, 0).getDate();
        let firstDayOfMonth = new Date(currentYear, i, 1).getDay(); // Primeiro dia do mês

        // Criando os dias do mês com espaços em branco no começo (se necessário)
        for (let d = 0; d < firstDayOfMonth; d++) {
            let emptyDay = document.createElement("div");
            emptyDay.classList.add("day");
            daysDiv.appendChild(emptyDay);
        }

        for (let d = 1; d <= daysInMonth; d++) {
            let dayDiv = document.createElement("div");
            dayDiv.classList.add("day");
            dayDiv.innerText = d;
            dayDiv.dataset.date = `${currentYear}-${(i + 1).toString().padStart(2, "0")}-${d.toString().padStart(2, "0")}`;
            dayDiv.onclick = () => openModal(dayDiv.dataset.date);
            daysDiv.appendChild(dayDiv);
        }

        monthDiv.appendChild(daysDiv);
        mesesDisplay.appendChild(monthDiv);
    });

    // Exibindo os outros meses abaixo
    for (let i = 0; i < 12; i++) {
        if (![previousMonth, currentMonth, nextMonth].includes(i)) {
            let monthDiv = document.createElement("div");
            monthDiv.classList.add("month");

            let monthTitle = document.createElement("h2");
            monthTitle.innerText = months[i];
            monthDiv.appendChild(monthTitle);

            let daysDiv = document.createElement("div");
            daysDiv.classList.add("days");

            // Adicionando os dias da semana
            let weekDays = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"];
            let weekDiv = document.createElement("div");
            weekDiv.classList.add("week");
            weekDays.forEach(day => {
                let dayDiv = document.createElement("div");
                dayDiv.classList.add("day");
                dayDiv.innerText = day;
                weekDiv.appendChild(dayDiv);
            });
            daysDiv.appendChild(weekDiv);

            // Obtendo os dias do mês
            let daysInMonth = new Date(currentYear, i + 1, 0).getDate();
            let firstDayOfMonth = new Date(currentYear, i, 1).getDay(); // Primeiro dia do mês

            // Criando os dias do mês com espaços em branco no começo (se necessário)
            for (let d = 0; d < firstDayOfMonth; d++) {
                let emptyDay = document.createElement("div");
                emptyDay.classList.add("day");
                daysDiv.appendChild(emptyDay);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                let dayDiv = document.createElement("div");
                dayDiv.classList.add("day");
                dayDiv.innerText = d;
                dayDiv.dataset.date = `${currentYear}-${(i + 1).toString().padStart(2, "0")}-${d.toString().padStart(2, "0")}`;
                dayDiv.onclick = () => openModal(dayDiv.dataset.date);
                daysDiv.appendChild(dayDiv);
            }

            monthDiv.appendChild(daysDiv);
            outrosMeses.appendChild(monthDiv);
        }
    }
});
