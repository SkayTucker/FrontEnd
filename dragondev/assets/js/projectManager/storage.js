const STORAGE_KEY = "project_list";

export function getProjects() {
  return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
}

export function saveProjects(projects) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(projects));
}

export function findProject(name) {
  return getProjects().find(p => p.name === name);
}
