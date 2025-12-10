document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.flash-message[data-auto-dismiss="true"]')
        .forEach(function (box) {
            setTimeout(function () {
                box.classList.add('flash-message--hidden');
            }, 4000); // 4 Sekunden sichtbar
        });
});