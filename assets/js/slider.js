document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".banner-slide");
    let current = 0;

    function nextSlide() {
        const oldSlide = current;
        current = (current + 1) % slides.length;

        slides[oldSlide].classList.remove("active");
        slides[oldSlide].classList.add("exit-left");

        slides[current].classList.add("active");

        setTimeout(() => {
            slides[oldSlide].classList.remove("exit-left");
        }, 1000);
    }

    setInterval(nextSlide, 5000); // Wechselt alle 5 Sekunden
});