// Header Scroll Behavior
const btnMenuToggle = document.getElementById("btnMenu");
const btnCloseMenu = document.getElementById("btnCloseMenu");
const menuWrapper = document.querySelector(".menuWrapper");

btnMenuToggle.addEventListener("click", () => {
  menuWrapper.classList.toggle("show");
  btnMenuToggle.classList.toggle("open");

  // Ajusta aria-expanded para acessibilidade
  const expanded = btnMenuToggle.getAttribute("aria-expanded") === "true";
  btnMenuToggle.setAttribute("aria-expanded", !expanded);
});

btnCloseMenu.addEventListener("click", () => {
  menuWrapper.classList.remove("show");
  btnMenuToggle.classList.remove("open");
  btnMenuToggle.setAttribute("aria-expanded", "false");
});