document.addEventListener('DOMContentLoaded', function () {
    // Flash Messages automatisch ausblenden
    document.querySelectorAll('.flash-message[data-auto-dismiss="true"]')
        .forEach(function (box) {
            setTimeout(function () {
                box.classList.add('flash-message--hidden');
            }, 4000);
        });

    // Telefon/Mobil: nur Zahlen, automatisch "+" vorne
    function enforcePlusDigits(input) {
        const fix = () => {
            const v = input.value || '';
            const digits = v.replace(/\D/g, ''); // alles außer Ziffern entfernen

            if (!digits) {
                input.value = '';
                return;
            }

            input.value = '+' + digits;
        };

        input.addEventListener('input', fix);
        input.addEventListener('blur', fix);
    }

    const mobil = document.querySelector('input[name="mobil"]');
    const telefon = document.querySelector('input[name="telefon"]');
    const plz = document.querySelector('input[name="plz"]');

    if (mobil) enforcePlusDigits(mobil);
    if (telefon) enforcePlusDigits(telefon);

    // PLZ: nur Zahlen
    if (plz) {
        plz.addEventListener('input', () => {
            plz.value = (plz.value || '').replace(/\D/g, '');
        });
    }
});
