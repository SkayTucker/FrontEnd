document.addEventListener("DOMContentLoaded", loadEvents);

function addEvent() {
    let date = document.getElementById("event-date").value;
    let text = document.getElementById("event-text").value;

    if (!date || !text) {
        alert("Preencha todos os campos!");
        return;
    }

    let newEvent = { date, text };
    saveEvent(newEvent);
    renderEvents();
}

function saveEvent(event) {
    let events = JSON.parse(localStorage.getItem("events")) || [];
    events.push(event);
    localStorage.setItem("events", JSON.stringify(events));
}

function loadEvents() {
    renderEvents();
}

function renderEvents() {
    let events = JSON.parse(localStorage.getItem("events")) || [];
    let eventList = document.getElementById("event-list");
    eventList.innerHTML = "";

    events.forEach(event => {
        let li = document.createElement("li");
        li.innerText = `${event.date}: ${event.text}`;
        eventList.appendChild(li);
    });
}
