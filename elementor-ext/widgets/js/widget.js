document.addEventListener('DOMContentLoaded', function () {
    console.log('DOMContentLoaded event fired');
    if (typeof Swiper !== 'undefined') {
        console.log('Swiper is defined');
        var swiperContainers = document.querySelectorAll('.swiper-container');

        swiperContainers.forEach(function (container) {
            var slidesPerView = container.dataset.slidesPerView || 1;
            var spaceBetween = container.dataset.spaceBetween || 0;
            var loop = container.dataset.loop === 'true';

            new Swiper(container, {
                slidesPerView: slidesPerView,
                spaceBetween: spaceBetween,
                loop: loop,
                centeredSlides: true, // Center the active slide
                slidesOffsetBefore: 0, // Set offset before first slide
                slidesOffsetAfter: 0, // Set offset after last slide
            });
        });
    } else {
        console.error('Swiper is not defined');
    }
});
