# DigiCal — Conference Calendar (Admin)

DigiCal is a WordPress plugin for managing multi-day conferences with venues and speakers, built for a modern, inline-editing admin workflow and a future-proof frontend.

- **Current baseline:** v1.4 (5 Nov 2025)
- **Next milestone:** v1.5 — Import/Export & Frontend Shortcodes

---

## Features (v1.4)

### Days
- DB-backed (no CSV), inline add/edit/delete
- Auto day name (localized), sorted groups
- Bulk select + bulk delete
- "Configure Day" button → per-day admin subpage
- Sidebar "Days" submenu auto-updates on save/delete

### Venues
- DB-backed venues (`Primary` & `Sub-venue`)
- Grouping: primary with sub-venues underneath
- **One-way address propagation:** editing a **primary** updates all its **sub-venues**; editing a sub-venue does **not** affect others
- Inline editing; bulk select + bulk delete
- Sort: grouped by primary name, then sub-venues by name

### Speakers
- DB-backed speakers with inline CRUD
- Speaker roles: Speaker / Moderator (configurable titles)
- Editable speaker titles (Prof., Dr., etc.) via Configuration panel
- Bulk delete & inline editing
- Role assignment and management UI

### General
- Fast AJAX admin (no page reload)
- Consistent blue WordPress look (#2271b1)
- Keyboard-friendly inline editing (no popups)
- Collapsible sections with expand/collapse controls
- Comprehensive dashboard with quick links

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
- `wp_digical_speakers`
- `wp_digical_titles`
- `wp_digical_roles`
- `wp_digical_speakers_roles` (junction table)

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

### Manage Speakers
1. Go to **DigiCal → Speakers**.
2. Enter speaker details:
   - **Title** (Prof., Dr., etc. — or select from Configuration presets)
   - **First Name** & **Last Name**
   - **Roles**: select Speaker and/or Moderator
3. Press **Save Speaker** — the table updates immediately.
4. Edit inline or bulk delete rows.
5. Configure custom titles via **Speakers → Configuration**.

---

## Roadmap (next versions)

- **v1.5 — Import/Export & Frontend:** Excel templates, JSON export, shortcode panel for agenda display.
- **v1.6 — Sessions/Schedule:** per-day timeline, tracks, conflicts, assignments (day/venue/speakers).
- **v1.7+ — Frontend Views:** filterable agenda (day, track, venue, speaker), responsive layouts, SEO fields.
- **v2.0+ — Commercial:** tickets, registration, multi-conference instances.

See `ROADMAP.md` for details.

---

## Changelog

See `CHANGELOG.md`.  
Current baseline: **v1.4** (5 Nov 2025).

---

## Troubleshooting

- **Tables not found / empty UI:** deactivate → activate plugin to re-run table ensure; or check DB user permissions.
- **AJAX not saving:** ensure you're logged in as an Administrator; browser dev-tools → Network → check `admin-ajax.php` responses.
- **Styling off:** clear any admin CSS caches; ensure `assets/css/admin.css` is enqueued.
- **Speakers not appearing:** verify DB tables exist; check PHP error logs for AJAX issues.

---

## License

© 2025 DIGIT, obrt za informatičke usluge.  
All rights reserved (internal project).
