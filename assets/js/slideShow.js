// const backgrounds = [
//     'assets/bg/1.png',
//     'assets/bg/2.png',
//     'assets/bg/3.png'
// ];

//pega todos os elementos da classe
const slides =document.querySelectorAll(".slides img");
//inicia a variável
let slideIndex = 0;
let intervalo = null;


function iniciarSlider(){
    //se o 
    if(slides.length > 0){
        slides[slideIndex].classList.add("mostrarSlide");
        intervalo = setInterval(nextSlide, 5000);
    }
}


function mostrarSlide(){
    if(index >= slides.length){
        slideIndex = 0;
    }else if (index < 0) {
        slideIndex = slides.length - 1;
    }

    slides.forEach(slide => {
        slide.classList.remove("mostrarSlide");
    })
}
// function changeBackground() {
//     //selecione no main o background-image = passa a `url($ARRAY[variável])`
//     document.getElementById('slides').style.backgroundImage = `url(${backgrounds[currentIndex]})`;
//     currentIndex = (currentIndex + 1) % backgrounds.length;
//     /*O operador % é o módulo, que retorna o resto da divisão. 
//     No contexto desse código, isso garante que o valor de 
//     currentIndex volte a 0 quando atingir o final da lista 
//     de imagens.*/
// }

// // Mudar a cada 5 segundos
// setInterval(changeBackground, 5000);

// // Definir o primeiro background assim que a página carregar
// window.onload = changeBackground;