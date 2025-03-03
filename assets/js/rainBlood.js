 // Rain of Blood Effect
 const canvas = document.getElementById('rain-canvas');
 const ctx = canvas.getContext('2d');
 canvas.width = window.innerWidth;
 canvas.height = window.innerHeight;

 const droplets = [];

 function createDroplet() {
   droplets.push({
     x: Math.random() * canvas.width,
     y: -10,
     length: Math.random() * 5 + 1,
     speed: Math.random() * 1 + 2,
   });
 }

 function drawDroplets() {
   ctx.clearRect(0, 0, canvas.width, canvas.height);
   ctx.strokeStyle = 'lightgray';
   ctx.lineWidth = 2;
   ctx.beginPath();
   droplets.forEach(droplet => {
     ctx.moveTo(droplet.x, droplet.y);
     ctx.lineTo(droplet.x, droplet.y + droplet.length);
   });
   ctx.stroke();
 }

 function updateDroplets() {
   droplets.forEach(droplet => {
     droplet.y += droplet.speed;
     if (droplet.y > canvas.height) {
       droplet.y = -droplet.length;
       droplet.x = Math.random() * canvas.width;
     }
   });
 }

 function animateRain() {
   drawDroplets();
   updateDroplets();
   requestAnimationFrame(animateRain);
 }

 // Start rain droplets one by one
 function startRain() {
   const interval = setInterval(() => {
     createDroplet();
     if (droplets.length >= 50) { // Limit to 100 droplets
       clearInterval(interval);
     }
   }, 30);
 }
// Rain of Blood Effect (mantém como está)
startRain();
animateRain();

// Typewriter Effect
const storyText = "Há muito tempo atrás, em um tempo nunca dantes imaginado, havia somente um globo em que toda criação foi concentrada. Como não existia nada para se comparar, o globo era grande, escuro e brilhante, tudo e nada.";
const storyElement = document.getElementById('story');
let index = 0;

function typeText() {
  if (index < storyText.length) {
    storyElement.textContent += storyText[index];
    index++;
    setTimeout(typeText, 40); // Velocidade da digitação
  }
}

// Inicia a digitação após 3 segundos
setTimeout(() => {
  typeText();
}, 3000);
