
/* =========================================================
   LIHI FAQ ACCORDION
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    const faqItems = document.querySelectorAll(".faq-item");

    if (!faqItems.length) return;

    // Open first FAQ by default
    faqItems.forEach((item, index) => {

        const trigger = item.querySelector(".faq-trigger");

        const isFirst = index === 0;

        item.classList.toggle("active", isFirst);

        if (trigger) {
            trigger.setAttribute(
                "aria-expanded",
                isFirst ? "true" : "false"
            );
        }

    });

    // Accordion click functionality
    faqItems.forEach((item) => {

        const trigger = item.querySelector(".faq-trigger");

        if (!trigger) return;

        trigger.addEventListener("click", () => {

            const isActive = item.classList.contains("active");

            // Close all FAQs
            faqItems.forEach((faq) => {

                faq.classList.remove("active");

                const button = faq.querySelector(".faq-trigger");

                if (button) {
                    button.setAttribute("aria-expanded", "false");
                }

            });

            // Open clicked FAQ if previously closed
            if (!isActive) {

                item.classList.add("active");

                trigger.setAttribute("aria-expanded", "true");

            }

        });

    });

});
