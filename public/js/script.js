// Menu burger
function menuOnClick() {
    document.getElementById("menu-bar").classList.toggle("change");
    document.getElementById("nav").classList.toggle("change");
    document.getElementById("menu-bg").classList.toggle("change-bg");
}

// Parallax

window.addEventListener('scroll', function() {
    var parallax = document.querySelector('.parallax-section');
    var scrollPosition = window.scrollY; // Utilise 'scrollY' à la place de 'pageYOffset'

    parallax.style.backgroundPosition = 'center ' + (scrollPosition * 0.1) + 'px';
});


// Carousel


document.addEventListener("DOMContentLoaded", function() {
    const carouselInner = document.querySelector(".carousel-inner");
    const carouselItems = document.querySelectorAll(".carousel-item");
    const carouselButtonsContainer = document.querySelector(".carousel-buttons");

    if (carouselInner && carouselItems.length > 0 && carouselButtonsContainer) {
        let currentIndex = 0;
        let interval;

        function updateCarousel() {
            carouselInner.style.transform = `translateX(-${currentIndex * 25}%)`;
        }

        function updateButtons() {
            carouselButtonsContainer.querySelectorAll(".carousel-button").forEach((button, index) => {
                if (index === currentIndex) {
                    button.classList.add("active");
                } else {
                    button.classList.remove("active");
                }
            });
        }

        function resetInterval() {
            clearInterval(interval);
            interval = setInterval(() => {
                currentIndex = (currentIndex + 1) % carouselItems.length;
                updateCarousel();
                updateButtons();
            }, 5000);
        }

        // Initialisation du carrousel
        updateCarousel();
        updateButtons();
        resetInterval();
    }
});


// Modal
function openModal(src) {
    document.getElementById('myModal').style.display = "block";
    document.getElementById('img01').src = src;
}

function closeModal() {
    document.getElementById('myModal').style.display = "none";
}

