const roulette = document.querySelector("#roulette");
const spinButton = document.querySelector("#spin");
const resetButton = document.querySelector("#reset");

const maxSpins = 10;
const minSpins = 1;

const maxDegrees = 360;
const minDegrees = 1;

// Categorías (orden igual al HTML)
const categories = [
    { id: 1, name: "Historia" },
    { id: 2, name: "Ciencia" },
    { id: 3, name: "Deportes" },
    { id: 4, name: "Geografía" },
    { id: 5, name: "Entretenimiento" }
];

const getRandomNumber = (min, max) => {
    return Math.round(Math.random() * (max - min) + min);
}

spinButton.addEventListener("click", () => {
    // Deshabilitar botón
    spinButton.disabled = true;

    fetch('/partida/girarRuleta')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.error);
                    spinButton.disabled = false;
                }
                return;
            }

            const targetCategoryId = data.category_id;
            const targetCategoryName = data.category_name;

            const categoryIndex = categories.findIndex(c => c.id === targetCategoryId);

            const spins = getRandomNumber(minSpins, maxSpins);
            const segmentSize = 360 / categories.length;

            const randomOffset = Math.random() * (segmentSize - 10) + 5; // Margen de 5px
            const targetRotation = 360 - (categoryIndex * segmentSize) - randomOffset;

            const fullSpins = (spins + 2) * 360; // Al menos 2 vueltas
            const totalSpin = fullSpins + targetRotation;

            const animationTime = 4; // 4 segundos fijo

            roulette.style.transform = `rotate(${totalSpin}deg)`;
            roulette.style.transitionDuration = `${animationTime}s`;

            spinButton.style.display = "none";

            setTimeout(() => {
                window.location.href = '/partida/jugarPregunta';
            }, animationTime * 1000 + 500); // 500ms extra
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al girar la ruleta.');
            spinButton.disabled = false;
        });
});

if (resetButton) {
    resetButton.addEventListener("click", () => {
        roulette.style.transform = "rotate(0deg)";
        roulette.style.transitionDuration = "2s";
        spinButton.style.display = "inline-block";
        resetButton.style.display = "none";
    });
}