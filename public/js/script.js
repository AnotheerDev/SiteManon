// Menu burger
function menuOnClick() {
    document.getElementById("menu-bar").classList.toggle("change");
    document.getElementById("nav").classList.toggle("change");
    document.getElementById("menu-bg").classList.toggle("change-bg");
}

// Parallax

window.addEventListener('scroll', function() {
    var parallax = document.querySelector('.parallax-section');
    var scrollPosition = window.scrollY; // Utilisez 'scrollY' à la place de 'pageYOffset'

    parallax.style.backgroundPosition = 'center ' + (scrollPosition * 0,1) + 'px';
});


// Carousel
document.addEventListener("DOMContentLoaded", function() {
    const carouselInner = document.querySelector(".carousel-inner");
    const carouselItems = document.querySelectorAll(".carousel-item");
    const prevButton = document.querySelector(".prev-button");
    const nextButton = document.querySelector(".next-button");

    let currentIndex = 0;

    prevButton.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + carouselItems.length) % carouselItems.length;
        updateCarousel();
        resetInterval();
    });

    nextButton.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % carouselItems.length;
        updateCarousel();
        resetInterval();
    });

    function updateCarousel() {
        carouselInner.style.transform = `translateX(-${currentIndex * 25}%)`;
    }

    let interval = setInterval(() => {
        currentIndex = (currentIndex + 1) % carouselItems.length;
        updateCarousel();
    }, 5000); // 5000 ms = 5 seconds

    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(() => {
            currentIndex = (currentIndex + 1) % carouselItems.length;
            updateCarousel();
        }, 5000); // 5000 ms = 5 seconds
    }
});


