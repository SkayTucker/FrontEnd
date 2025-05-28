document.addEventListener("DOMContentLoaded", function () {
    const slideTrack = document.querySelector(".customSlideTrack");
    const slideItems = Array.from(slideTrack.children);
    const slideWidth = slideTrack.scrollWidth;
  
    slideItems.forEach((item) => {
      const clone = item.cloneNode(true);
      slideTrack.appendChild(clone);
    });
  
    let movePixels = 1;
  
    function animateSlide() {
      movePixels -= 1;
      slideTrack.style.transform = `translateX(${movePixels}px)`;
  
      if (Math.abs(movePixels) >= slideWidth / 5) {
        movePixels = 0;
      }
  
      requestAnimationFrame(animateSlide);
    }
  
    animateSlide();
  });
  