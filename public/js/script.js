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

// Clicks sur les topics
document.addEventListener('DOMContentLoaded', function() {
    const topics = document.querySelectorAll('.topic-item');

    topics.forEach(topic => {
        topic.addEventListener('click', function(event) {
            // event.preventDefault(); // Empêche le comportement par défaut si .topic est un lien ou un bouton
            const topicId = this.dataset.id; // Récupère l'id du topic

            fetch(`/topic/${topicId}/click`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // 'X-CSRF-TOKEN': 'Votre-CSRF-Token' // Si nécessaire
                },
                // body: JSON.stringify({}) // Si besoin d'envoyer des données supplémentaires
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Met à jour le compteur de clics dans le DOM
                const clickCountElement = this.querySelector('.click-count');
                if (clickCountElement) {
                    clickCountElement.textContent = `Clicks: ${data.clickCount}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // afficher une notification à l'utilisateur
            });
        });
    });
});


// reCaptcha

function onSubmitRegister(token) {
    document.getElementById("register-form").submit(); 
    // ID correspond à l'ID du formulaire d'inscription
}


function onSubmitContact(token) {
    document.getElementById("contact-form").submit(); 
    // ID correspond à l'ID du formulaire de contact
}


    var alerts = document.querySelectorAll(".alert");
        alerts.forEach(alert => {
            setTimeout(function() {
                alert.style.display = "none";
            }, 5000);
        })