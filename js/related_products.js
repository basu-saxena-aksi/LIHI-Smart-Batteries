document.addEventListener("DOMContentLoaded", function () {

    const carousels = document.querySelectorAll(".related-products");

    carousels.forEach(function (carousel) {

        const track = carousel.querySelector(
            ".related-products-track"
        );

        const cards = carousel.querySelectorAll(
            ".related-product-card"
        );

        const prevButton = carousel.querySelector(
            ".related-products-prev"
        );

        const nextButton = carousel.querySelector(
            ".related-products-next"
        );

        if (
            !track ||
            !cards.length ||
            !prevButton ||
            !nextButton
        ) {
            return;
        }


        let currentIndex = 0;


        function getVisibleCards() {

            if (window.innerWidth <= 640) {
                return 1;
            }

            if (window.innerWidth <= 1024) {
                return 2;
            }

            return 4;
        }


        function updateCarousel() {

            const visibleCards = getVisibleCards();

            const maxIndex = Math.max(
                0,
                cards.length - visibleCards
            );

            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }

            const cardWidth =
                cards[0].getBoundingClientRect().width;

            const gap = 18;

            const moveAmount =
                currentIndex * (cardWidth + gap);

            track.style.transform =
                `translateX(-${moveAmount}px)`;


            /* Disable buttons when needed */

            prevButton.disabled =
                currentIndex === 0;

            nextButton.disabled =
                currentIndex >= maxIndex;
        }


        nextButton.addEventListener("click", function () {

            const visibleCards = getVisibleCards();

            const maxIndex = Math.max(
                0,
                cards.length - visibleCards
            );

            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }

        });


        prevButton.addEventListener("click", function () {

            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }

        });


        window.addEventListener(
            "resize",
            updateCarousel
        );


        updateCarousel();

    });

});