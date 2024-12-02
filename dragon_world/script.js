document.addEventListener("DOMContentLoaded", (event) => {
  gsap.registerPlugin(ScrollToPlugin, ScrollTrigger, Flip,TextPlugin, EasePack);

  /* Main navigation */
  let panelsSection = document.querySelector("#panels"),
    panelsContainer = document.querySelector("#panels-container"),
    tween;

  let scrollFunc = ScrollTrigger.getScrollFunc(window);
  let button = document.querySelector(".menu-button");
  let bg = document.querySelector(".menu-background");
  let menuText = document.querySelector(".menu-text");
  let fullscreenTitle = document.querySelector(".fullscreen-title");
  var container = $("#demo"),
    _sentenceEndExp = /(\.|\?|!)$/g; //regular expression to sense punctuation that indicates the end of a sentence so that we can adjust timing accordingly


  document.querySelectorAll(".anchor").forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      let targetElem = document.querySelector(e.target.getAttribute("href")),
        y = targetElem;
      if (targetElem && panelsContainer.isSameNode(targetElem.parentElement)) {
        let totalScroll = tween.scrollTrigger.end - tween.scrollTrigger.start,
          totalMovement = (panels.length - 1) * targetElem.offsetWidth;
        y = Math.round(
          tween.scrollTrigger.start +
            (targetElem.offsetLeft / totalMovement) * totalScroll
        );
      }
      gsap.to(window, {
        scrollTo: {
          y: y,
          autoKill: false,
        },
        onStart: () => (scrollFunc.cacheID = Math.random()),
        onUpdate: ScrollTrigger.update,
        duration: 1,
      });
    });
  });

  /* Panels */
  const panels = gsap.utils.toArray("#panels-container .panel");
  tween = gsap.to(panels, {
    xPercent: -100 * (panels.length - 1),
    ease: "none",
    scrollTrigger: {
      trigger: "#panels-container",
      pin: true,
      start: "top top",
      scrub: 1,
      anticipatePin: 1,
      snap: {
        snapTo: 1 / (panels.length - 1),
        inertia: false,
        duration: { min: 0.1, max: 0.1 },
      },
      end: () => "+=" + (panelsContainer.offsetWidth - innerWidth),
    },
  });

  button.addEventListener("click", (e) => {
    // Grab state
    const state = Flip.getState(".menu-background");

    bg.classList.toggle("big");

    // Toggle text visibility
    if (bg.classList.contains("big")) {
      menuText.style.display = "none"; // Hide button text
      fullscreenTitle.textContent = "Menu fullscreen"; // Set fullscreen title
    } else {
      menuText.style.display = "block"; // Show button text
      fullscreenTitle.textContent = "Menu"; // Clear fullscreen title
    }

    // Animate from the initial state to the end state
    Flip.from(state, { duration: 0.8, ease: "expo.inOut", spin: true, scale: true });
  });





function machineGun(text) {
  var words = text.split(" "),
      tl = gsap.timeline({delay:0.6, repeat:2, repeatDelay:4}),
      wordCount = words.length,
      time = 0,
      word, element, duration, isSentenceEnd, i;
  
  for(i = 0; i < wordCount; i++){
    word = words[i];
    isSentenceEnd = _sentenceEndExp.test(word);
    element = $("<h3>" + word + "</h3>").appendTo(container);
    duration = Math.max(0.5, word.length * 0.08); //longer words take longer to read, so adjust timing. Minimum of 0.5 seconds.
    if (isSentenceEnd) {
      duration += 0.6; //if it's the last word in a sentence, drag out the timing a bit for a dramatic pause.
    }
    //set opacity and scale to 0 initially. We set z to 0.01 just to kick in 3D rendering in the browser which makes things render a bit more smoothly.
    gsap.set(element, {autoAlpha:0, scale:0, z:0.01});
    //the SlowMo ease is like an easeOutIn but it's configurable in terms of strength and how long the slope is linear. See https://www.greensock.com/v12/#slowmo and https://api.greensock.com/js/com/greensock/easing/SlowMo.html
    tl.to(element, duration, {scale:1.2,  ease:"slow(0.25, 0.9)"}, time)
      //notice the 3rd parameter of the SlowMo config is true in the following tween - that causes it to yoyo, meaning opacity (autoAlpha) will go up to 1 during the tween, and then back down to 0 at the end. 
      .to(element, duration, {autoAlpha:1, ease:"slow(0.25, 0.9, true)"}, time);
    time += duration - 0.05;
    if (isSentenceEnd) {
      time += 0.6; //at the end of a sentence, add a pause for dramatic effect.
    }
  }
  
}

machineGun("These words are constantly animating in your face to suck you in and leave you hanging for what comes next. Can you handle the awesomeness? Or are you left quivering in fear? It's only text, silly. Longer words stay on the screen for a greater duration. Each sentence ends with a dramatatic pause. Yes, that pause. The End");

/* learn more about the GreenSock Animation Platfrom (GSAP) for JS

https://www.greensock.com/gsap-js/

watch a quick video on how TimelineLite allows you to sequence animations like a pro
https://www.greensock.com/sequence-video/

*/

//Check out this enhanced version that intelligently groups words together: https://codepen.io/GreenSock/pen/sxdfe
});
