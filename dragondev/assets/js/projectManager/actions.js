import { getProjects, saveProjects, findProject } from "./storage.js";

export function deleteProjectPrompt() {
  const projects = getProjects();
  if (!projects.length) {
    alert("Não há projetos para excluir.");
    return;
  }

  let list = "Escolha o número do projeto para excluir:\n";
  projects.forEach((p, i) => (list += `${i + 1} - ${p.name}\n`));

  const input = prompt(list);
  if (!input) return;

  const index = parseInt(input, 10) - 1;
  if (isNaN(index) || index < 0 || index >= projects.length) {
    alert("Opção inválida.");
    return;
  }

  const projectName = projects[index].name;
  if (!confirm(`Tem certeza que deseja excluir o projeto "${projectName}"?`)) return;

  saveProjects(projects.filter(p => p.name !== projectName));
  alert(`Projeto "${projectName}" excluído.`);
}

export function updateStats() {
  const projects = getProjects();

  const totalProjects = projects.length;
  const totalTasks = projects.reduce((acc, p) => acc + p.tasks.length, 0);
  const completedTasks = projects.reduce((acc, p) => acc + p.tasks.filter(t => t.done).length, 0);
  const pendingTasks = totalTasks - completedTasks;

  document.getElementById("totalProjects").textContent = totalProjects;
  document.getElementById("totalTasks").textContent = totalTasks;
  document.getElementById("completedTasks").textContent = completedTasks;
  document.getElementById("pendingTasks").textContent = pendingTasks;
}
