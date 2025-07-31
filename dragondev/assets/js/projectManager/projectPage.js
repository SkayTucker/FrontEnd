import { getProjects, saveProjects } from './storage.js';

export function renderProjectPage(container, projectName, onBack, updateStats) {
  const projects = getProjects();
  const project = projects.find(p => p.name === projectName);

  if (!project) {
    container.innerHTML = "<p>Projeto não encontrado.</p>";
    return;
  }

  container.innerHTML = "";

  // Criar wrapper principal
  const wrapper = document.createElement("div");
  wrapper.classList.add("tasks-content");

  const title = document.createElement("h2");
  title.textContent = project.name;

  const description = document.createElement("p");
  description.textContent = project.description;

  const taskTitle = document.createElement("h3");
  taskTitle.textContent = "Tarefas";

  const taskList = document.createElement("ul");

  project.tasks.forEach((task, i) => {
    const li = document.createElement("li");

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.id = `task_${i}`;
    checkbox.checked = Boolean(task.done);
    checkbox.addEventListener("change", () => {
      task.done = checkbox.checked;
      saveProjects(projects);
      updateStats();
      renderProjectPage(container, projectName, onBack, updateStats);
    });

    const label = document.createElement("label");
    label.htmlFor = checkbox.id;
    label.textContent = task.title;
    label.style.textDecoration = task.done ? "line-through" : "none";
    label.style.color = task.done ? "#888" : "";

    li.append(checkbox, label);
    taskList.appendChild(li);
  });

  const newTaskInput = document.createElement("input");
  newTaskInput.type = "text";
  newTaskInput.placeholder = "Nova tarefa...";

  const addTaskBtn = document.createElement("button");
  addTaskBtn.textContent = "Adicionar";
  addTaskBtn.addEventListener("click", () => {
    const val = newTaskInput.value.trim();
    if (!val) {
      alert("Digite uma tarefa.");
      return;
    }
    project.tasks.push({ title: val, done: false });
    saveProjects(projects);
    updateStats();
    renderProjectPage(container, projectName, onBack, updateStats);
  });

  const backBtn = document.createElement("button");
  backBtn.textContent = "Voltar";
  backBtn.addEventListener("click", onBack);

  // Adiciona todos os elementos ao wrapper
  wrapper.append(title, description, taskTitle, taskList, newTaskInput, addTaskBtn, backBtn);

  // Adiciona wrapper ao container principal
  container.appendChild(wrapper);
}
