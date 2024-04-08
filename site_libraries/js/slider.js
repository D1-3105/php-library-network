var slideIndex = 0;

function showSlides() {
    var i;
    var slides = $(".slide");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        slides[i].className = "slide";
    }

    if (slideIndex >= slides.length) {slideIndex = 0}
    if (slideIndex < 0) {slideIndex = slides.length - 1}

    $(slides[slideIndex]).css("display", "block");  
    $(slides[slideIndex]).addClass("active");
}

function plusSlides(n=1) {
    slideIndex += n;
    showSlides();
}

function minusSlides(n=1) {
    slideIndex -= n;
    showSlides();
}

$(document).ready(function(){
    setInterval(function() {
        plusSlides(1); // Переключение слайдов каждые 5000 миллисекунд (5 секунд)
    }, 5000);
    showSlides(); // Показать первый слайд при загрузке страницы
});
