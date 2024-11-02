var navHeader = document.getElementById("headerMenu");
var bodyMain = document.getElementById("mainBody");
var menuBtn = document.getElementById("openMenu");
var showSideBar = false;

function sideBar() {
    showSideBar = !showSideBar;//verdadeiro
    console.log(showSideBar);
    
    if (showSideBar) {
        navHeader.style.marginLeft = "1px"
        navHeader.style.animationName = 'showSideBar'
        bodyMain.style.filter = "blur(5px)"
        menuBtn.style.background = "gold"

    }else{
        navHeader.style.marginLeft = "-45vw"
        navHeader.style.animationName = ""
        bodyMain.style.filter = ""
        menuBtn.style.background = ""
    }
    

}


function sideBarClose() {
    if(showSideBar){
        sideBar();
    }
}


window.addEventListener("resize", function(event) {
    if(window.innerWidth > 540 && showSideBar) {
        sideBar();
    }
})







