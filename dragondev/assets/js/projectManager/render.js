import { getProjects, saveProjects } from "./storage.js";

export function renderProjectList(container, onProjectClick) {
  const projects = getProjects();

  container.innerHTML = "";
  if (projects.length === 0) {
    container.innerHTML = "<p>Nenhum projeto criado ainda.</p>";
    return;
  }

  const cardsContainer = document.createElement("div");
  cardsContainer.className = "projects-cards-container";

  projects.forEach(project => {
    const totalTasks = project.tasks.length;
    const doneTasks = project.tasks.filter(t => t.done).length;
    const percentDone = totalTasks ? Math.round((doneTasks / totalTasks) * 100) : 0;

    const card = document.createElement("div");
    card.className = "project-card";
    card.tabIndex = 0;
    card.setAttribute("role", "button");
    card.addEventListener("click", () => onProjectClick(project.name));
    card.addEventListener("keypress", e => {
      if (e.key === "Enter" || e.key === " ") onProjectClick(project.name);
    });

    const title = document.createElement("h3");
    title.className = "project-card-title";
    title.textContent = project.name;

    const description = document.createElement("p");
    description.className = "project-card-description";
    description.textContent = project.description;

    const progressContainer = document.createElement("div");
    progressContainer.className = "project-progress-container";

    const progressBar = document.createElement("div");
    progressBar.className = "project-progress-bar";
    progressBar.style.width = percentDone + "%";

    const progressText = document.createElement("span");
    progressText.className = "project-progress-text";
    progressText.textContent = `${percentDone}% concluído`;

    progressContainer.append(progressBar, progressText);
    card.append(title, description, progressContainer);
    cardsContainer.appendChild(card);
  });

  container.appendChild(cardsContainer);
}

export function renderProjectPage(container, projectName, onBack, updateStats) {
  const projects = getProjects();
  const project = projects.find(p => p.name === projectName);

  if (!project) {
    container.innerHTML = "<p>Projeto não encontrado.</p>";
    return;
  }

  container.innerHTML = "";

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

  container.append(title, description, taskTitle, taskList, newTaskInput, addTaskBtn, backBtn);
}
