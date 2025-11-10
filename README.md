# DigiCal — Conference Calendar (Admin)

DigiCal is a WordPress plugin for managing multi-day conferences with venues, speakers, and session scheduling, built for a modern, inline-editing admin workflow and a future-proof frontend.

- **Current baseline:** v1.6 (10 Nov 2025)
- **Next milestone:** v1.7 — Frontend Shortcodes & Filterable Agenda

---

## Features (v1.6)

### Days & Day Plans
- DB-backed (no CSV), inline add/edit/delete
- Auto day name (localized), sorted groups
- Bulk select + bulk delete
- **Per-day "Day Plan" tab** with session management
- Session modal (3-step): Basic Info → Times & Location → Speaker & Roles
- Dynamic sidebar submenu auto-updates on save/delete

### Session Types
- Configurable session type categories (Workshop, Lecture, Panel, etc.)
- **5 icon categories** with solid silhouette conference icons (20 total icons)
- Masonry grid UI with color gradients
- Checkbox-based selection in session modal
- Bulk delete functionality
- Edit/Save mode with visual feedback

### Sessions (Day Plan Modal)
- **3-step modal interface** for adding/editing sessions:
  1. Basic Info (title, description, session type)
  2. Times & Location (start/end times, venue selection)
  3. Speaker & Roles (role-based speaker filtering, participant assignment)
- Role-based speaker filtering: dropdowns show only speakers with selected roles
- Participant display system with role count badges ("3 Keynotes", "1 Moderator")
- Session type icons display in modal
- Database persistence (wp_digical_sessions, wp_digical_session_roles)

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

1. Upload the plugin folder to `/wp-content/plugins/digical/`  
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
- `wp_digical_sessions` *(new in v1.6)*
- `wp_digical_session_roles` *(new in v1.6)* (junction table)
- `wp_digical_session_types` *(new in v1.6)*

*(Table prefix depends on your WordPress `$wpdb->prefix`.)*

---

## How to Use

### Manage Days
1. Go to **DigiCal → Days**.
2. Enter **Date** (DDMMYYYY), **Start** and **End** (HH or HH:MM).
3. Press **Save Day** — the table and sidebar update immediately.
4. Click cells to **edit inline**; press **Enter** to save.
5. Select multiple rows to enable **Delete Selected**.
6. Click **Configure Day** to open the **Day Plan** tab for session management.

### Day Plan — Manage Sessions
1. Go to **DigiCal → Days → Configure Day** (or use sidebar link).
2. Click **Add Session** to open the 3-step modal:
   - **Step 1: Basic Info** — Enter session title, description, and select session types
   - **Step 2: Times & Location** — Set start/end times and pick primary/sub-venues
   - **Step 3: Speaker & Roles** — Select roles, filter speakers by role, assign participants
3. Assigned participants display as badges with role counts (e.g., "3 Keynotes")
4. Click **Save Session** to persist to database
5. Edit or delete sessions inline in the day plan table

### Manage Session Types
1. Go to **DigiCal → Session Types**.
2. Browse predefined session type categories with icons:
   - **Keynote** (5 icons showing podium/speaker scenes)
   - **Workshop** (5 icons showing hands-on/collaboration)
   - **Panel** (5 icons showing multiple speakers)
   - **Breakout** (5 icons showing smaller groups)
   - **Social** (5 icons showing networking/social scenes)
3. Click icon to select; checkbox indicates selection
4. Use **Bulk Delete** to remove multiple types
5. Click **Edit** to enter edit mode, then **Save** to persist changes
6. Session types are used in the session modal (Step 1)

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

- **v1.7 — Frontend Shortcodes:** Agenda display shortcodes, filterable by day/venue/speaker.
- **v1.8 — Advanced Sessions:** Conflict detection, multi-track support, speaker assignments.
- **v2.0+ — Commercial:** Tickets, registration, multi-conference instances.

See `ROADMAP.md` for details.

---

## Changelog

See `CHANGELOG.md`.  
Current baseline: **v1.6** (10 Nov 2025).

---

## Troubleshooting

- **Tables not found / empty UI:** deactivate → activate plugin to re-run table ensure; or check DB user permissions.
- **AJAX not saving:** ensure you're logged in as an Administrator; browser dev-tools → Network → check `admin-ajax.php` responses.
- **Styling off:** clear any admin CSS caches; ensure `assets/css/admin.css` is enqueued.
- **Session modal issues:** verify `wp_digical_sessions` and `wp_digical_session_roles` tables exist; check PHP error logs.
- **Speakers not appearing in role filter:** confirm speakers have roles assigned in the Speakers section.

---

## License

© 2025 DIGIT, obrt za informatičke usluge.  
All rights reserved (internal project).
