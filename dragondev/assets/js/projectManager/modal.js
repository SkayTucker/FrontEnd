import { saveProjects, findProject, getProjects } from './storage.js';
import { updateStats } from './stats.js';
import { renderProjectList } from './projectList.js'; // se usar renderProjectList no modal



let modalTasks = [];
let modalImported = false;

// Renderiza as tarefas no modal, com opção de remover só se não importado
export function renderTasksInModal() {
  const container = document.getElementById("tasksContainer");
  container.innerHTML = "";

  modalTasks.forEach((task, idx) => {
    const div = document.createElement("div");
    div.className = "task-item";

    const label = document.createElement("label");
    label.textContent = task;
    div.appendChild(label);

    if (!modalImported) {
      const btnRemove = document.createElement("button");
      btnRemove.textContent = "✖";
      btnRemove.title = "Remover tarefa";
      btnRemove.type = "button";
      btnRemove.addEventListener("click", () => {
        modalTasks.splice(idx, 1);
        renderTasksInModal();
        checkSaveButton();
      });
      div.appendChild(btnRemove);
    }

    container.appendChild(div);
  });

  checkSaveButton();
}

// Retorna uma cópia das tarefas do modal
export function getTasksFromModal() {
  return [...modalTasks];
}

// Habilita/desabilita botão salvar conforme condições:
// nome e descrição preenchidos, e
// (algum json importado OU pelo menos 1 tarefa manual)
export function checkSaveButton() {
  const btnSave = document.getElementById("btnSaveProject");
  const name = document.getElementById("projectName").value.trim();
  const description = document.getElementById("projectDescription").value.trim();
  const tasksOk = modalImported || modalTasks.length > 0;

  btnSave.disabled = !(name && description && tasksOk);
}


// Salva o novo projeto se estiver válido
export function saveNewProjectFromModal() {
  const name = document.getElementById("projectName").value.trim();
  const description = document.getElementById("projectDescription").value.trim();
  const tasks = getTasksFromModal();

  console.log("saveNewProjectFromModal:", { name, description, tasks, modalImported });

  if (!name || !description || (tasks.length === 0 && !modalImported)) {
    alert("Preencha todos os campos e insira pelo menos uma tarefa ou importe um arquivo.");
    return false;
  }
  if (findProject(name)) {
    alert("Projeto com esse nome já existe.");
    return false;
  }

  const tasksObj = tasks.map(t => ({ title: t, done: false }));

  const projects = getProjects();
  projects.push({ name, description, tasks: tasksObj });
  saveProjects(projects);

  return true;
}

// Configura os listeners do modal (importar arquivo, adicionar tarefa, inputs)
export function setupModalListeners() {
  const taskFileInput = document.getElementById("taskFileInput");
  const btnAddTask = document.getElementById("btnAddTask");
  const projectNameInput = document.getElementById("projectName");
  const projectDescriptionInput = document.getElementById("projectDescription");

  if (taskFileInput) {
    taskFileInput.addEventListener("change", e => {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = evt => {
        try {
          const json = JSON.parse(evt.target.result);
          if (!Array.isArray(json)) throw new Error("JSON deve ser um array de strings");
          if (json.some(t => typeof t !== "string")) throw new Error("Todas as tasks devem ser strings.");

          modalTasks = json;
          modalImported = true;
          renderTasksInModal();
          alert("Tasks importadas com sucesso!");
          taskFileInput.value = "";
        } catch (err) {
          alert("Erro ao importar JSON: " + err.message);
        }
      };
      reader.readAsText(file);
    });
  }

  if (btnAddTask) {
    btnAddTask.addEventListener("click", () => {
      const task = prompt("Digite a nova tarefa:");
      if (!task) {
        alert("Tarefa não pode ser vazia.");
        return;
      }

      if (modalImported) {
        modalImported = false;
        modalTasks = [];
      }

      modalTasks.push(task.trim());
      renderTasksInModal();
      checkSaveButton();  // Atualiza o estado do botão Salvar
    });
  }

  // Escuta alterações em nome e descrição para habilitar botão salvar
  [projectNameInput, projectDescriptionInput].forEach(input => {
    if (input) input.addEventListener("input", checkSaveButton);
  });
}


// Abre o modal limpando os dados anteriores
export function openNewProjectModal() {
  modalTasks = [];
  modalImported = false;

  document.getElementById("projectName").value = "";
  document.getElementById("projectDescription").value = "";

  const taskFileInput = document.getElementById("taskFileInput");
  if (taskFileInput) taskFileInput.value = "";

  renderTasksInModal();
  checkSaveButton();

  document.getElementById("modalOverlay").classList.remove("hidden");
}


// Fecha o modal
export function closeNewProjectModal() {
  document.getElementById("modalOverlay").classList.add("hidden");
}


export function deleteProjectPrompt() {
  const projects = getProjects();
  if (projects.length === 0) {
    alert("Não há projetos para excluir.");
    return;
  }

  let list = "Escolha o número do projeto para excluir:\n";
  projects.forEach((p, i) => {
    list += `${i + 1} - ${p.name}\n`;
  });

  const input = prompt(list);
  if (!input) return;

  const index = parseInt(input, 10) - 1;
  if (isNaN(index) || index < 0 || index >= projects.length) {
    alert("Opção inválida.");
    return;
  }

  const projectName = projects[index].name;
  if (!confirm(`Tem certeza que deseja excluir o projeto "${projectName}"?`)) return;

  const newProjects = projects.filter((_, i) => i !== index);
  saveProjects(newProjects);

  alert(`Projeto "${projectName}" excluído.`);

  // Atualiza lista e estatísticas após exclusão
  const mainContent = document.getElementById("mainContent");
  renderProjectList(mainContent, projectName => {
    // Renderiza a página do projeto após exclusão (ou lista novamente)
    // Se quiser, adapte essa função conforme sua lógica
  });
  updateStats();
}