document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.cbr-form');

    if (!form) {
        return;
    }

    var submitButton = form.querySelector('.cbr-submit');

    form.addEventListener('submit', function () {
        if (!form.checkValidity()) {
            return;
        }

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.setAttribute('aria-busy', 'true');
            submitButton.querySelector('span').textContent = 'SUBMITTING REQUEST...';
        }
    });
});
