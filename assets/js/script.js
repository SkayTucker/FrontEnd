/*Aprendendo GSAP*/
/*
  gsap.to()- Este é o tipo mais comum de tween. 
  Um .to() o tween começará no estado atual do elemento 
  e animará "para" os valores definidos no tween.

  gsap.from()- Como um backwards .to() onde ele anima
   "a partir" dos valores definidos na interpolação e
    termina no estado atual do elemento.  

  gsap.fromTo() - Você define os valores inicial e
  final.

  gsap.set() Define propriedades imediatamente
  (sem animação). É essencialmente um .to() tween 
   de duração zero.
=====================================================
        ============
        = synthax =
        ============

    // use a class or ID
    gsap.to(".box", { x: 200 });

    // a complex CSS selector
    gsap.to("section > .box", { x: 200 });
    gsap.to(".box", { rotation: 360, duration: 2s,});
    gsap.to(".box", {rotation: 360, x: 10, yPercent: 50});

    // a variable
    let box = document.querySelector(".box");
    gsap.to(box, { x: 200 })

    // or even an Array of elements
    let square = document.querySelector(".square");
    let circle = document.querySelector(".circle");
                                        
    gsap.to([square, circle], { x: 200 })
=======================================================
   */





/*MANTER ESTRUTURA E APRENDER JS*/



document.addEventListener('DOMContentLoaded', (event) => {
    /*BOX*/
    gsap.to("body", {
        duration: 2,
        backgroundColor: '#8d3dae',
        repeat: -1,
        yoyo: true,

    });
    gsap.to("header", {rotation: 360, x: 10, yPercent: 100});
    gsap.to("footer", {scaleX: 2});

});
