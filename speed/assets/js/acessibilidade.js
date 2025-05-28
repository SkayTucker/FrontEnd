document.addEventListener("DOMContentLoaded", function () {
    const darkModeBtn = document.querySelector(".dropDownPcd-content a:nth-child(1)");
    const lowContrastBtn = document.querySelector(".dropDownPcd-content a:nth-child(2)");
  
    darkModeBtn.addEventListener("click", function (event) {
      event.preventDefault();
      document.body.classList.toggle("dark-mode");
    });
  
    lowContrastBtn.addEventListener("click", function (event) {
      event.preventDefault();
      document.body.classList.toggle("low-contrast");
    });
  
    // Para exibir o menu ao passar o mouse
    const dropDownMenu = document.getElementById("dropDownPcd");
    dropDownMenu.addEventListener("mouseenter", function () {
      dropDownMenu.style.display = "block";
    });
  
    dropDownMenu.addEventListener("mouseleave", function () {
      dropDownMenu.style.display = "none";
    });
  });
  