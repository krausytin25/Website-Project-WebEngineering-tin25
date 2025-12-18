function initHeader(container) {
    const menuToggle = container.querySelector('.menu-toggle');
    const mainNav = container.querySelector('.main-nav');
    const submenuBtn = container.querySelector('.submenu-toggle');
    const submenu = container.querySelector('.submenu');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', () => {
            const open = mainNav.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    if (submenuBtn && submenu) {
        submenuBtn.addEventListener('click', () => {
            const open = submenu.classList.toggle('is-open');
            submenuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    if (header) initHeader(header);
});