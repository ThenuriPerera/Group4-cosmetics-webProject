# Start here

Read README.md for the current WAMP setup and folder structure.

This version replaces the previous stylesheet with organized shared and page-specific styles. Extract into a clean folder, keep your working database settings and reload with Ctrl+F5.

Useful documents:
- docs/FILE-MAP.md — which controller, template and stylesheet to edit.
- docs/DEBUGGING.md — check paths, syntax, database mismatches and known issues in order.
- docs/VALIDATION.md — checks completed here and checks still needed in WAMP.

The 50 generated catalogue product images are included in `assets/images/products/`. Fresh installs link them through `database/schema.sql`; for an existing compatible database, run `database/link_all_50_product_images.sql`.
