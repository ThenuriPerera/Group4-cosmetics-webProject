# Luminé Glow — Group 04

Vanilla HTML, CSS, JavaScript, PHP and MySQL. No frameworks or new external APIs.

## Start with WAMP

1. Back up your current folder and database.
2. Extract this complete project into C:\wamp64\www\test\Group4-cosmetics-webProject.
3. Keep your locally working config/db.php settings if they differ from this archive.
4. Start WAMP, wait for a green icon, then open:
   http://localhost/test/Group4-cosmetics-webProject/index.php
5. Press Ctrl+F5 to reload cached styles.

Use a clean folder for this version: page templates and styles have been reorganized. You do not need to edit hardcoded URL prefixes or set up a virtual host. Do not import either design reference's database over your existing database.

## Where to work

| Folder | Responsibility |
| --- | --- |
| `modules/` | Existing PHP entry points: form handling, database queries and redirects |
| `views/` | Complete HTML/PHP template for each page |
| `layouts/` | Shared document header, navigation, breadcrumbs and footer |
| `config/pages.php` | Page title, description, stylesheet and script mapping |
| `config/paths.php` | WAMP folder-aware links and image URL helper |
| `includes/` | Existing authentication and Stripe helpers; layout entry points |
| `assets/css/base.css` | Colours and typography |
| `assets/css/layout.css` | Shared header, page layout and footer |
| `assets/css/components.css` | Forms, buttons, tables, cards and feedback |
| `assets/css/pages/` | Home, auth, catalogue, beauty, account, shopping and admin styling |
| `assets/js/main.js` | Shared mobile navigation and page behaviour |
| `assets/js/pages/` | Cart, quiz and shade-finder behaviour |
| `assets/images/dp.png` | Your reference project's hero image |
| `database/` | Original schema, retained unchanged |
| `docs/` | File map, debugging order and validation report |
| `tools/` | Read-only checks you can run locally |

Read docs/FILE-MAP.md to find a particular page, and docs/DEBUGGING.md when something fails.

## Working rules

Keep processing in modules and markup in views. Keep styles in the matching CSS file. Keep page scripts in assets/js/pages. Do not duplicate full headers, copy large inline style blocks, or add page scripts inside templates.

The existing modules URLs remain valid. Payment callbacks, logout and cart-ajax.php are action endpoints; they do not have independent display templates. Existing Git history is retained. No commits were made or pushed.

## Status

All 18 display templates (home plus 17 module pages) use the reference design system. Original processing is preserved. Known backend defects, including the duplicate wishlist handler, are documented rather than silently changed. This is a frontend redesign and file reorganization, not a claim that all assignment features are complete.
