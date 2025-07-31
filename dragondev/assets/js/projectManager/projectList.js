import { getProjects } from './storage.js';

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
