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

    gsap.registerPlugin(ScrollToPlugin, ScrollTrigger);

    /* Main navigation */
    let panelsSection = document.querySelector("#panels"),
        panelsContainer = document.querySelector("#panels-container"),
        tween;
    document.querySelectorAll(".anchor").forEach(anchor => {
        anchor.addEventListener("click", function(e) {
            e.preventDefault();
            let targetElem = document.querySelector(e.target.getAttribute("href")),
                y = targetElem;
            if (targetElem && panelsContainer.isSameNode(targetElem.parentElement)) {
                let totalScroll = tween.scrollTrigger.end - tween.scrollTrigger.start,
                    totalMovement = (panels.length - 1) * targetElem.offsetWidth;
                y = Math.round(tween.scrollTrigger.start + (targetElem.offsetLeft / totalMovement) * totalScroll);
            }
            gsap.to(window, {
                scrollTo: {
                    y: y,
                    autoKill: false
                },
                duration: 1
            });
        });
    });
    
    /* Panels */
    const panels = gsap.utils.toArray("#panels-container .panel");
    tween = gsap.to(panels, {
        xPercent: -100 * ( panels.length - 1 ),
        ease: "none",
        scrollTrigger: {
            trigger: "#panels-container",
            pin: true,
            start: "top top",
            scrub: 1,
            snap: {
                snapTo: 1 / (panels.length - 1),
                inertia: false,
                duration: {min: 0.1, max: 0.1}
            },
            end: () =>  "+=" + (panelsContainer.offsetWidth - innerWidth)
        }
    });
    
    

});