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
    const spins = getRandomNumber(minSpins, maxSpins);
    const degrees = getRandomNumber(minDegrees, maxDegrees);

    const fullSpins = (spins - 1) * 360;
    const spin = fullSpins + degrees;

    const animationTime = spins;

    roulette.style.transform = `rotate(${spin}deg)`;
    roulette.style.transitionDuration = `${animationTime}s`;

    spinButton.style.display = "none";
    resetButton.style.display = "inline-block";

    // Espera a que termine la animación para calcular el resultado
    setTimeout(() => {
        const finalRotation = spin % 360;
        const segmentSize = 360 / categories.length;

        // Determina el índice del sector (ajuste por dirección del giro)
        const selectedIndex = Math.floor((360 - finalRotation) / segmentSize) % categories.length;
        const selectedCategory = categories[selectedIndex];

        alert("Categoría: " + selectedCategory.name);

        // Redirige al backend
        window.location.href = `/partida/mostrarPregunta?category_id=${selectedCategory.id}`;
    }, animationTime * 1000);
});

resetButton.addEventListener("click", () => {
    roulette.style.transform = "rotate(0deg)";
    roulette.style.transitionDuration = "2s";
    spinButton.style.display = "inline-block";
    resetButton.style.display = "none";
});