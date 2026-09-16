// Shared presentation only; module cart and quiz handlers stay in their files.
document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('#main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') !== 'true';
            toggle.setAttribute('aria-expanded', String(open));
            nav.classList.toggle('is-open', open);
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
    }

    const here = location.pathname.endsWith('/')
        ? location.pathname + 'index.php'
        : location.pathname;

    document.querySelectorAll('#main-nav a,.section-nav a,.admin-sidebar a')
        .forEach(link => {
            if (new URL(link.href).pathname === here) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            }
        });

    document.querySelectorAll('[data-confirm]').forEach(link => {
        link.addEventListener('click', event => {
            if (!window.confirm(link.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('table').forEach(table => {
        const wrapper = document.createElement('div');
        wrapper.className = 'table-scroll';
        wrapper.tabIndex = 0;
        wrapper.setAttribute('role', 'region');
        wrapper.setAttribute('aria-label', 'Scrollable table');
        table.before(wrapper);
        wrapper.append(table);
    });

    document.querySelectorAll('img').forEach(image => {
        const showPlaceholder = () => {
            if (!image.isConnected) {
                return;
            }

            const placeholder = document.createElement('div');
            placeholder.className = 'product-placeholder';
            placeholder.textContent = 'Photo coming soon';
            image.replaceWith(placeholder);
        };

        image.addEventListener('error', showPlaceholder, { once: true });

        if (image.complete && !image.naturalWidth) {
            showPlaceholder();
        }
    });

    // Let the soft page glow follow a mouse slightly.
    // Skip touch devices and users who prefer reduced motion.
    if (
        window.matchMedia &&
        !window.matchMedia('(prefers-reduced-motion: reduce)').matches &&
        window.matchMedia('(pointer: fine)').matches
    ) {
        let ambientFrame = 0;
        const root = document.documentElement;

        const resetAmbient = () => {
            root.style.setProperty('--ambient-x', '0px');
            root.style.setProperty('--ambient-y', '0px');
        };

        window.addEventListener('pointermove', event => {
            if (ambientFrame) {
                window.cancelAnimationFrame(ambientFrame);
            }

            ambientFrame = window.requestAnimationFrame(() => {
                const x = ((event.clientX / window.innerWidth) - 0.5) * 22;
                const y = ((event.clientY / window.innerHeight) - 0.5) * 18;

                root.style.setProperty('--ambient-x', `${x.toFixed(1)}px`);
                root.style.setProperty('--ambient-y', `${y.toFixed(1)}px`);
            });
        }, { passive: true });

        window.addEventListener('pointerleave', resetAmbient, { passive: true });
    }
});