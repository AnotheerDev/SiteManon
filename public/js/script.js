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

    parallax.style.backgroundPosition = 'center ' + (scrollPosition * 0,1) + 'px';
});


// Carousel



document.addEventListener("DOMContentLoaded", function() {
    const carouselInner = document.querySelector(".carousel-inner");
    const carouselItems = document.querySelectorAll(".carousel-item");
    // const prevButton = document.querySelector(".prev-button");
    // const nextButton = document.querySelector(".next-button");
    const carouselButtonsContainer = document.querySelector(".carousel-buttons");

    let currentIndex = 0;
    let interval;

    carouselItems.forEach((item, index) => {
        const button = document.createElement("button");
        button.className = "carousel-button";
        if (index === 0) {
            button.classList.add("active");
        }
        button.addEventListener("click", () => {
            currentIndex = index;
            updateCarousel();
            updateButtons();
            resetInterval();
        });
        carouselButtonsContainer.appendChild(button);
    });

    // prevButton.addEventListener("click", () => {
    //     currentIndex = (currentIndex - 1 + carouselItems.length) % carouselItems.length;
    //     updateCarousel();
    //     updateButtons();
    //     resetInterval();
    // });

    // nextButton.addEventListener("click", () => {
    //     currentIndex = (currentIndex + 1) % carouselItems.length;
    //     updateCarousel();
    //     updateButtons();
    //     resetInterval();
    // });

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

    interval = setInterval(() => {
        currentIndex = (currentIndex + 1) % carouselItems.length;
        updateCarousel();
        updateButtons();
    }, 5000);

    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(() => {
            currentIndex = (currentIndex + 1) % carouselItems.length;
            updateCarousel();
            updateButtons();
        }, 5000);
    }
});

