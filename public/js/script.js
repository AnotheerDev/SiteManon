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