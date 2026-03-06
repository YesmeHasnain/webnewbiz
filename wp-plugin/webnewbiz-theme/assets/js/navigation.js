/**
 * WebnewBiz Theme — Mobile navigation toggle.
 */
(function () {
    const toggle = document.querySelector('.menu-toggle');
    const menu = document.getElementById('primary-menu');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', function () {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !expanded);
        toggle.classList.toggle('toggled');
        menu.classList.toggle('toggled');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.main-navigation') && menu.classList.contains('toggled')) {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.classList.remove('toggled');
            menu.classList.remove('toggled');
        }
    });
})();
