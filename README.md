# Luminé Glow — Cosmetic E-Commerce System
Group 04 | Web Application Development | 2024/2025

Vanilla HTML5, CSS3, JavaScript, PHP 8, MySQL — no frameworks.

## Module Ownership (matches Section 8 entities)

| Member | Module | Folder(s) | Tables owned |
|---|---|---|---|
| K.M.S.R.Dissanayaka | Auth & User Management | `modules/auth/`, `includes/auth.php` | User, Address |
| V.H.M.Dananjalie | Product Catalog & Smart Features | `modules/products/` | Product, Product_Variant, Category, Brand, Skin_Quiz, Beauty_Profile |
| K.A.D.T.N.Perera | Cart, Checkout & Payment | `modules/cart/` | Cart, Cart_Item, Order, Order_Item, Payment, Promo_Code |
| I.P.T Aravindi | Orders, Reviews, Wishlist & Admin | `modules/orders/`, `modules/admin/` | Order_History, Shipment, Courier, Review, Wishlist |

Shared files (`config/db.php`, `includes/header.php`, `includes/footer.php`, `assets/css/style.css`,
`assets/js/main.js`, `database/schema.sql`) — edit carefully, pull before you touch these.

## Local Setup (each member)

1. Install XAMPP (or MAMP/WAMP) — gives you Apache, PHP, MySQL.
2. Clone the repo into `htdocs/lumine-glow`.
3. Start Apache + MySQL in XAMPP control panel.
4. Open `http://localhost/phpmyadmin`, create a database, then import `database/schema.sql`.
5. Visit `http://localhost/lumine-glow/index.php`.

## Git Workflow (one repo, 4 collaborators, individually visible commits)

**Setup (once, done by whoever creates the repo on GitHub):**
```
git init
git add .
git commit -m "Initial project skeleton"
git branch -M main
git remote add origin https://github.com/<owner>/lumine-glow.git
git push -u origin main
```
Then on GitHub: **Settings → Collaborators** → add the other 3 members' GitHub usernames
(or make it an Organization repo and add everyone as a team — either works for lecturer commit checks).

**Every other member, once:**
```
git clone https://github.com/<owner>/lumine-glow.git
cd lumine-glow
```

**Daily workflow — use a feature branch per person/feature, don't commit straight to `main`:**
```
git checkout main
git pull origin main
git checkout -b feature/auth-login          # e.g. feature/cart-checkout, feature/reviews

# ...edit your module's files...

git add modules/auth/login.php
git commit -m "auth: add login validation and session role redirect"
git push origin feature/auth-login
```
Then open a Pull Request on GitHub into `main`, and merge once reviewed (even a self-review is fine for
a class project — the point is the commit history shows individual authorship).

**Commit message convention** (so the lecturer can tell who did what at a glance):
```
<module>: <what changed>

auth: hash passwords with password_hash()
products: implement 3-level category filter
cart: add AJAX quantity update
orders: build order history timeline UI
```

**Rules to avoid conflicts:**
- Stay inside your own `modules/<yourmodule>/` folder as much as possible.
- If you need to touch a shared file (`db.php`, `header.php`, `style.css`, `schema.sql`), `git pull`
  right before, make a small focused change, and push quickly — don't sit on shared-file edits for days.
- Each member should have **commits every work session**, not one giant commit at the end — that's usually
  what "checking commits" is verifying (real, incremental individual contribution).
- Pull before you push, always: `git pull origin main --rebase` if you get conflicts.

## What's already scaffolded vs. what's TODO

Every file under `modules/` has a `TODO` comment block at the top marking what that member still needs to
build (validation, AJAX, real payment API calls, admin charts, etc). The skeleton runs end-to-end
(register → browse → add to cart → checkout → mock payment → order created) so you can each build on
top of a working base instead of empty files.
