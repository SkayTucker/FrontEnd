document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".tarefa input").forEach(checkbox => {
        checkbox.addEventListener("change", () => {
            const tarefaId = checkbox.getAttribute("data-id");
            const concluido = checkbox.checked ? 1 : 0;

            const formData = new FormData();
            formData.append("tarefa_id", tarefaId);
            formData.append("concluido", concluido);

            fetch("update_task.php", {
                method: "POST",
                body: formData
            })
            .catch(error => console.error("Erro ao atualizar tarefa:", error));
        });
    });
});
