$('.testimonials.carousel').owlCarousel({
    loop: false,
    margin: 60,
    nav: false,
    autoplay: true,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        },
        1600: {
            items: 5
        }
    }
});