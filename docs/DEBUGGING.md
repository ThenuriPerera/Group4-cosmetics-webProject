# Debug in this order

## 1. Confirm the URL

Open http://localhost/test/Group4-cosmetics-webProject/index.php through WAMP. Do not use file://, a .view.php URL, or VS Code Live Server.

If a page is unstyled, open http://localhost/test/Group4-cosmetics-webProject/assets/css/base.css. CSS text should appear. A 404 means the folder was not extracted at that location. Check config/paths.php only after confirming the actual folder and address.

Use Ctrl+F5. In the browser Network panel, check the four CSS requests and main.js. A relevant page script should load once, not twice.

## 2. Run read-only local checks

Open PowerShell in the project folder and run:

```powershell
.\tools\check-wamp.ps1
```

The script locates installed WAMP PHP and runs PHP syntax checks, asset checks and root/subfolder URL tests. It does not modify your database. If local policy prevents PowerShell scripts, use your WAMP PHP executable directly:

```text
php tools/check-project.php
```

To additionally inspect existing database columns:

```powershell
.\tools\check-wamp.ps1 -Database
```

Or run `php tools/check-project.php --database`. These optional database checks use SHOW only. Keep WAMP's database service running and config/db.php configured for your existing database.

## 3. Find the correct file

Use FILE-MAP.md. Appearance problem: inspect the view and its page stylesheet. Query, validation or redirect problem: inspect the module entry point. Shared link problem: inspect config/paths.php. Titles or wrong page stylesheet: inspect config/pages.php.

CSS defines --rose as the original reference rose. --rose-text is a darker shade used where readability matters. Serif and sans-serif stacks use local Georgia/Arial fallbacks because the reference did not include font files; no external font API is used.

## 4. Existing backend issues to address separately

These are present in the supplied Group4 code and were not changed by this frontend reorganization.

| Symptom | Evidence / file | Next investigation |
| --- | --- | --- |
| Wishlist shows order information | modules/orders/wishlist.php duplicates the tracking handler | Replace that handler with proper per-user Wishlist queries before considering the wishlist complete |
| Category filter or editor query fails | Original schema.sql lacks Product.sub_category and Product.product_type | Inspect your live Product columns; plan a non-destructive migration matching the existing controller |
| Cart fails with unknown image column | Cart query explicitly selects Product.image | Check whether the live database calls it image_url; display fallback alone does not fix SQL column names |
| Payment amount currency differs from labels | payment.php uses USD, while templates label prices Rs. | Agree the intended currency and test gateway conversion/amounts before real payment use |
| Variant price not reflected in bag | Cart joins Product.price | Review variant-aware price calculation across cart and payment together |
| Profile or quiz does not update as expected | Beauty_Profile uniqueness may not match ON DUPLICATE KEY UPDATE assumptions | Inspect schema constraints and duplicate rows before changing anything |

Do not import a reference database to fix a missing column. It may use different table names and user/session structures. Back up your current database before a separately reviewed migration.

## 5. Check the browser interaction

- Mobile menu: open, close and press Escape.
- Login/register: required fields, server feedback, redirects.
- Catalogue: search and all three filter levels.
- Product: variant, quantity and existing form submissions.
- Quiz: next/back and final submission.
- Shade finder: selection, keyboard focus and result.
- Bag: quantity change, removal, final empty state, failed-request feedback.
- Checkout: choose or add an address.
- Payment: sandbox only while existing amount/currency issues remain.
- Orders: empty state, history and shipment details.
- Admin/editor: forms, tables, role restrictions and moderation.

The new cart script reports failed requests and restores the displayed quantity when saving fails. It uses the same existing endpoint and action names.
