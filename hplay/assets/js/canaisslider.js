const track = document.querySelector('.fightCardSliderTrack');
const leftBtn = document.querySelector('.leftBtn');
const rightBtn = document.querySelector('.rightBtn');

let scrollAmount = 0;
const cardWidth = 200; // Largura + gap

rightBtn.addEventListener('click', () => {
  scrollAmount += cardWidth;
  track.scrollTo({
    left: scrollAmount,
    behavior: 'smooth'
  });
});

leftBtn.addEventListener('click', () => {
  scrollAmount -= cardWidth;
  if (scrollAmount < 0) scrollAmount = 0;
  track.scrollTo({
    left: scrollAmount,
    behavior: 'smooth'
  });
});
