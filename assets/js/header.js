function initHeader(container) {
    const menuToggle = container.querySelector('.menu-toggle');
    const mainNav    = container.querySelector('.main-nav');
    const submenuBtn = container.querySelector('.submenu-toggle');
    const submenu    = container.querySelector('.submenu');

    // Hamburger-Menü
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', () => {
            const open = mainNav.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    // Abteilungen Dropdown
    if (submenuBtn && submenu) {
        submenuBtn.addEventListener('click', () => {
            const open = submenu.classList.toggle('is-open');
            submenuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }
}

// Header laden & danach initHeader aufrufen
fetch("../src/components/header.html")
    .then(response => response.text())
    .then(html => {
        const headerContainer = document.getElementById("header");
        headerContainer.innerHTML = html;
        initHeader(headerContainer);   // WICHTIG: erst NACH dem Einfügen initialisieren
    })
    .catch(err => console.error("Header konnte nicht geladen werden:", err));
