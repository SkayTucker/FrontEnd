import { renderProjectList } from './projectList.js';
import { renderProjectPage } from './projectPage.js';
import {
    saveNewProjectFromModal,
    deleteProjectPrompt,
    setupModalListeners,
    openNewProjectModal,
    closeNewProjectModal,
} from './modal.js';
import { updateStats } from './stats.js';

document.addEventListener("DOMContentLoaded", () => {
    setupModalListeners();

    const mainContent = document.getElementById("mainContent");

    // Função para renderizar lista de projetos e adicionar callback de clique
    function showProjectList() {
        renderProjectList(mainContent, showProjectPage);
        updateStats();
    }

    // Função para renderizar página do projeto e configurar botão voltar
    function showProjectPage(projectName) {
        renderProjectPage(mainContent, projectName, showProjectList, updateStats);
    }

    // Evento abrir modal de novo projeto
    document.getElementById("btnOpenCreateModal")?.addEventListener("click", openNewProjectModal);

    // Evento cancelar modal
    document.getElementById("btnCancelProject")?.addEventListener("click", closeNewProjectModal);

    // Evento salvar novo projeto no modal
    document.getElementById("btnSaveProject")?.addEventListener("click", () => {
        if (saveNewProjectFromModal()) {
            closeNewProjectModal();
            updateStats();
            showProjectList();
        }
    });

    // Evento excluir projeto
    document.getElementById("btnDeleteProject")?.addEventListener("click", () => {
        deleteProjectPrompt();
        updateStats();
        showProjectList();
    });

    // Renderização inicial da lista de projetos
    showProjectList();
});
