# DigiCal – Changelog

---

## Version 1.4
**Release Date:** 5 Nov 2025

### New Features
- **Speakers Module** (Complete)
  - DB table: `wp_digical_speakers` with title, first_name, last_name, bio
  - Inline CRUD operations (add, edit, delete)
  - Bulk delete functionality
  - Speaker roles (Speaker / Moderator) via junction table
  - Configuration panel for custom titles (Prof., Dr., etc.)
  - Role management UI with visual chips

- **Configuration Panel**
  - Manage speaker titles globally
  - Manage speaker roles
  - Easy add/edit/delete of custom options

- **Dashboard**
  - Overview with quick links to Days, Venues, Speakers
  - Module status indicators
  - Planned feature roadmap display

### Improvements
- UI/UX: Collapsible sections for better organization
- Admin menu: Configuration submenu under Speakers
- AJAX performance: optimized speaker CRUD endpoints
- Database: schema updates handled on plugin activation and load

### Technical
- New AJAX handlers in `admin/speakers-ajax-db.php`
- New AJAX handlers in `admin/titles-roles-ajax-db.php`
- DB schema migrations (auto-applied on plugin activation)
- Enhanced error logging for debugging

---

## Version 1.2
**Release Date:** 31 Oct 2025

### New Features
- **Venues Module** (Complete)
  - DB table: `wp_digical_venues` with type, name, address, parent_id
  - Primary & Sub-venue venue types
  - One-way address propagation (primary → sub-venues)
  - Inline editing with real-time updates
  - Bulk delete functionality
  - Grouped table display

### Improvements
- Sorted venue table (alphabetically by primary, then sub-venues)
- Enhanced AJAX to prevent page reloads
- Better styling consistency with WordPress admin theme

---

## Version 1.1
**Release Date:** 31 Oct 2025

### New Features
- **Days Module** (Initial)
  - DB table: `wp_digical_days` with date, start_time, end_time
  - Inline add/edit/delete operations
  - Auto day name generation (localized to Croatian)
  - Bulk select & bulk delete
  - Dynamic sidebar submenu
  - Per-day admin subpages
  - Flexible time entry (HH or HH:MM format)

### Technical
- Admin menu structure established
- AJAX foundation (fast, no page reloads)
- Consistent WordPress admin blue theme (#2271b1)
- Keyboard-friendly inline editing

---

## Version 1.0
**Release Date:** 31 Oct 2025

### Initial Release
- Plugin scaffolding and activation hooks
- Menu structure (General, Days, Venues, Speakers, Configuration)
- Placeholder pages

---

© 2025 DIGIT
