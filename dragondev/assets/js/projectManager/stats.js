import { getProjects } from './storage.js';

export function updateStats() {
  const projects = getProjects();

  const totalProjects = projects.length;
  const totalTasks = projects.reduce((acc, p) => acc + p.tasks.length, 0);
  const completedTasks = projects.reduce((acc, p) => acc + p.tasks.filter(t => t.done).length, 0);
  const pendingTasks = totalTasks - completedTasks;

  const elTotalProjects = document.getElementById("totalProjects");
  const elTotalTasks = document.getElementById("totalTasks");
  const elCompletedTasks = document.getElementById("completedTasks");
  const elPendingTasks = document.getElementById("pendingTasks");

  if (elTotalProjects) elTotalProjects.textContent = totalProjects;
  if (elTotalTasks) elTotalTasks.textContent = totalTasks;
  if (elCompletedTasks) elCompletedTasks.textContent = completedTasks;
  if (elPendingTasks) elPendingTasks.textContent = pendingTasks;
}
