document.addEventListener("DOMContentLoaded", () => {
    const form          = document.getElementById("add-entry-form");
    const typeSelect    = document.getElementById("entry-type");

    // Termin-Felder
    const eventGroup    = document.querySelector(".add-entry__field-group--event");

    // News-Bild-Feld
    const imageGroup    = document.querySelector(".add-entry__field-group--image");
    const imageInput    = document.getElementById("entry-image");
    const imagePreview  = document.getElementById("entry-image-preview");

    function updateVisibility() {
        const isEvent = typeSelect.value === "event";
        const isNews  = typeSelect.value === "news";

        // Termin sichtbar?
        eventGroup.classList.toggle("add-entry__field-group--event-hidden", !isEvent);

        // News-Bild sichtbar?
        imageGroup.classList.toggle("add-entry__field-group--image-hidden", !isNews);

        if (!isNews) {
            imageInput.value = "";
            imagePreview.innerHTML = "";
        }
    }

    typeSelect.addEventListener("change", updateVisibility);
    updateVisibility();

    // Live-Preview (optional)
    imageInput.addEventListener("change", () => {
        const file = imageInput.files[0];
        if (!file) {
            imagePreview.innerHTML = "";
            return;
        }
        const url = URL.createObjectURL(file);
        imagePreview.innerHTML = `<img src="${url}" alt="Bildvorschau">`;
    });

    // Speichern irgendwann?
    form.addEventListener("submit", (e) => {
        if (!form.reportValidity()) {
            e.preventDefault();     // nur blockieren, WENN ungültig
        }
    });

    // Flash-Messages (Erfolg/Fehler) automatisch ausblenden
    const flashes = document.querySelectorAll('.flash-message[data-auto-dismiss="true"]');
    flashes.forEach((flash) => {
        // nach 3 Sekunden langsam ausblenden
        setTimeout(() => {
            flash.classList.add('flash-message--hidden');
        }, 3000);

        // nach 4 Sekunden komplett entfernen (optional)
        setTimeout(() => {
            if (flash.parentNode) {
                flash.parentNode.removeChild(flash);
            }
        }, 4000);
    });
});
