class Carousel {

    constructor (element, options = {}) {
        console.log('ok');
    }

}

// Attend que la page ait fini de charger
document.addEventListener('DOMContentLoaded', function () {
    new Carousel(document.getElementById('carousel')), {
        slidesToScroll: 3,
        slidesVisible: 3
    }
})