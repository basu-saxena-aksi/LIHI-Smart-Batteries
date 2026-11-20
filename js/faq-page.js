document.addEventListener("DOMContentLoaded", () => {

    const faqSection = document.querySelector(".faq-page-section");

    if (!faqSection) return;

    const tabs = faqSection.querySelectorAll(".faq-tab");
    const panels = faqSection.querySelectorAll(".faq-page-panel");


    /* -----------------------------------------
       OPEN FIRST QUESTION IN EVERY CATEGORY
    ----------------------------------------- */

    panels.forEach(panel => {

        const firstItem = panel.querySelector(".faq-page-item");

        if (firstItem) {

            firstItem.classList.add("active");

            const question = firstItem.querySelector(".faq-page-question");

            if (question) {
                question.setAttribute("aria-expanded", "true");
            }

        }

    });


    /* -----------------------------------------
       CATEGORY SWITCHING
    ----------------------------------------- */

    tabs.forEach(tab => {

        tab.addEventListener("click", () => {

            const category = tab.dataset.category;

            tabs.forEach(item => {

                const isActive = item === tab;

                item.classList.toggle("active", isActive);
                item.setAttribute("aria-selected", isActive ? "true" : "false");

            });

            panels.forEach(panel => {

                const isActive = panel.dataset.panel === category;

                panel.classList.toggle("active", isActive);

            });

        });

    });


    /* -----------------------------------------
       ACCORDION OPEN / CLOSE
    ----------------------------------------- */

    panels.forEach(panel => {

        const questions = panel.querySelectorAll(".faq-page-question");

        questions.forEach(question => {

            question.addEventListener("click", () => {

                const currentItem = question.closest(".faq-page-item");

                if (!currentItem) return;

                const isAlreadyOpen = currentItem.classList.contains("active");

                // Close other questions in this category
                panel.querySelectorAll(".faq-page-item.active").forEach(item => {

                    item.classList.remove("active");

                    const otherQuestion = item.querySelector(".faq-page-question");

                    if (otherQuestion) {
                        otherQuestion.setAttribute("aria-expanded", "false");
                    }

                });

                // Open clicked question if it was previously closed
                if (!isAlreadyOpen) {

                    currentItem.classList.add("active");

                    question.setAttribute("aria-expanded", "true");

                }

            });

        });

    });

});