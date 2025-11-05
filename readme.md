# DigiCal — Conference Calendar (Admin)

DigiCal is a WordPress plugin for managing multi-day conferences with venues and (next) speakers, built for a modern, inline-editing admin workflow and a future-proof frontend.

- **Current baseline:** v1.1 (31 Oct 2025)
- **Next milestone:** v1.2 — Speakers module

---

## Features (v1.1)

### Days
- DB-backed (no CSV), inline add/edit/delete
- Auto day name (localized), sorted groups
- Bulk select + bulk delete
- “Configure Day” button → per-day admin subpage
- Sidebar “Days” submenu auto-updates on save/delete

### Venues
- DB-backed venues (`Primary` & `Sub-venue`)
- Grouping: primary with sub-venues underneath
- **One-way address propagation:** editing a **primary** updates all its **sub-venues**; editing a sub-venue does **not** affect others
- Inline editing; bulk select + bulk delete
- Sort: grouped by primary name, then sub-venues by name

### General
- Fast AJAX admin (no page reload)
- Consistent blue WordPress look (#2271b1)
- Keyboard-friendly inline editing (no popups)

---

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Logged-in admin (capability `manage_options`)

No special PHP extensions required (standard WP stack).

---

## Installation

1. Upload the plugin folder to `/wp-content/plugins/digi-cal/`  
   or install the ZIP via **Plugins → Add New → Upload Plugin**.
2. Activate **DigiCal**.
3. Navigate to **DigiCal** in the WP Admin menu.

On activation, DigiCal ensures its tables exist:
- `wp_digical_days`
- `wp_digical_venues`

*(Table prefix depends on your WordPress `$wpdb->prefix`.)*

---

## How to Use

### Manage Days
1. Go to **DigiCal → Days**.
2. Enter **Date** (DDMMYYYY), **Start** and **End** (HH or HH:MM).
3. Press **Save Day** — the table and sidebar update immediately.
4. Click cells to **edit inline**; press **Enter** to save.
5. Select multiple rows to enable **Delete Selected**.

### Manage Venues
1. Go to **DigiCal → Venues**.
2. Choose **Venue Type**:
   - **Primary**: standalone venue (parent).
   - **Sub-venue**: child of a primary venue.
3. If **Sub-venue**, pick a **Primary venue** (address can auto-fill).
4. Press **Save Venue** — the table updates immediately.
5. Edit inline; bulk delete available.
6. **Propagation rule:** changing a **primary** address updates **all** its sub-venues. Editing a **sub-venue** affects only that row.

---

## Roadmap (next versions)

- **v1.2 — Speakers:** roles (speaker/moderator), inline CRUD, linking to sessions.
- **v1.3 — Sessions/Schedule:** per-day timeline, tracks, conflicts, assignments (day/venue/speakers).
- **v1.4+ — Frontend:** filterable agenda (day, track, venue, speaker), responsive layouts, shortcodes.

See `Features Roadmap.md` for details.

---

## Changelog

See `Changelog.md`.  
Current baseline: **v1.1** (31 Oct 2025).

---

## Troubleshooting

- **Tables not found / empty UI:** deactivate → activate plugin to re-run table ensure; or check DB user permissions.
- **AJAX not saving:** ensure you’re logged in as an Administrator; browser dev-tools → Network → check `admin-ajax.php` responses.
- **Styling off:** clear any admin CSS caches; ensure `assets/css/admin.css` is enqueued.

---

## License

© 2025 DIGIT, obrt za informatičke usluge.  
All rights reserved (internal project).
