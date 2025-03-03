function openModal(date) {
    document.getElementById("modal").style.display = "flex";
    document.getElementById("selected-date").innerText = `Data selecionada: ${date}`;
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

function selectType(type) {
    alert(`Você escolheu: ${type}`);
    closeModal();
}
