# Page and file map

Every entry point prepares variables, sets its page key, and loads the shared header, its template and the shared footer. Templates must be opened through their entry points; directly requesting a .view.php file returns 404.

| Page key | Entry point / processing | Template / presentation |
| --- | --- | --- |
| home | `index.php` | `views/home.view.php` |
| auth/login | `modules/auth/login.php` | `views/auth/login.view.php` |
| auth/profile | `modules/auth/profile.php` | `views/auth/profile.view.php` |
| auth/register | `modules/auth/register.php` | `views/auth/register.view.php` |
| products/beauty-quiz | `modules/products/beauty-quiz.php` | `views/products/beauty-quiz.view.php` |
| products/shade-finder | `modules/products/shade-finder.php` | `views/products/shade-finder.view.php` |
| products/index | `modules/products/index.php` | `views/products/index.view.php` |
| products/product | `modules/products/product.php` | `views/products/product.view.php` |
| products/manage | `modules/products/manage.php` | `views/products/manage.view.php` |
| orders/reviews | `modules/orders/reviews.php` | `views/orders/reviews.view.php` |
| orders/wishlist | `modules/orders/wishlist.php` | `views/orders/wishlist.view.php` |
| orders/track-order | `modules/orders/track-order.php` | `views/orders/track-order.view.php` |
| cart/payment | `modules/cart/payment.php` | `views/cart/payment.view.php` |
| cart/checkout | `modules/cart/checkout.php` | `views/cart/checkout.view.php` |
| cart/cart | `modules/cart/cart.php` | `views/cart/cart.view.php` |
| cart/payment-cancel | `modules/cart/payment-cancel.php` | `views/cart/payment-cancel.view.php` |
| admin/review-moderation | `modules/admin/review-moderation.php` | `views/admin/review-moderation.view.php` |
| admin/dashboard | `modules/admin/dashboard.php` | `views/admin/dashboard.view.php` |

## Styles and behaviour

| Page family | Stylesheet | JavaScript |
| --- | --- | --- |
| Home | assets/css/pages/home.css | assets/js/main.js |
| Login, register | assets/css/pages/auth.css | assets/js/main.js |
| Catalogue, product details | assets/css/pages/catalogue.css | assets/js/main.js |
| Quiz, shade finder | assets/css/pages/beauty.css | assets/js/pages/beauty-quiz.js or shade-finder.js |
| Profile, orders, wishlist, reviews | assets/css/pages/account.css | assets/js/main.js |
| Bag, checkout, payment, cancellation | assets/css/pages/shopping.css | assets/js/pages/cart.js on the bag |
| Product management, dashboard, moderation | assets/css/pages/admin.css | assets/js/main.js |

Styles load in this order: base.css, layout.css, components.css, selected page-family CSS. Edit the earliest appropriate file. Page-specific exceptions belong in pages/, not a second global stylesheet.

The old assets/css/style.css is only a compatibility import manifest. The current shared header loads the named files directly.

## Actions without templates

| Endpoint | Role |
| --- | --- |
| modules/auth/logout.php | Ends the session and redirects |
| modules/cart/cart-ajax.php | Returns JSON for quantity updates and removal |
| modules/cart/payment-success.php | Verifies payment server-side and redirects |

## Request order

1. Browser opens a module entry point.
2. Entry point loads database and authentication helpers.
3. Existing request processing and queries run.
4. Entry point sets pageKey.
5. includes/header.php loads config/pages.php and layouts/header.php.
6. Matching views/...view.php renders using the prepared variables.
7. includes/footer.php loads layouts/footer.php.
8. Browser executes the selected deferred JavaScript files.

Do not move view files without updating their module's require path. Moving a module URL requires updating navigation, redirects, form actions and payment return URLs.
