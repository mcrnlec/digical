# DigiCal – Changelog

---

## Version 1.6
**Release Date:** 10 Nov 2025

### New Features

- **Session Types Module** (Complete)
  - DB table: `wp_digical_session_types` with icon categories and color gradients
  - 5 session type categories (Keynote, Workshop, Panel, Breakout, Social)
  - 20 solid silhouette conference icons (4 per category) replacing Material Design
  - Masonry grid UI with color gradients and hover effects
  - Checkbox-based selection for bulk operations
  - Bulk delete functionality
  - Edit/Save mode with green button and visual feedback
  - No confirmation dialogs for streamlined UX

- **Sessions & Day Plan Modal** (Complete)
  - DB table: `wp_digical_sessions` with day_id, venue_id, title, times, session_level
  - Junction table: `wp_digical_session_roles` (session_id, role_id, speaker_id)
  - **3-Step Modal Interface:**
    - Step 1: Basic Info (title, description, session type selection)
    - Step 2: Times & Location (start/end times, primary + sub-venue dropdowns)
    - Step 3: Speaker & Roles (role checkboxes, filtered speaker dropdowns, participant badges)
  - Role-based speaker filtering: dropdowns show only speakers with selected roles
  - Participant display with role count badges ("3 Keynotes", "1 Moderator")
  - Dynamic sidebar Day Plan pages linked to each conference day
  - Session icons display in modal and tables

- **Database Enhancements**
  - New `wp_digical_sessions` table: session_id, day_id, venue_id, title, description, start_time, end_time, session_level, created_at, updated_at
  - New `wp_digical_session_roles` junction table: session_id, role_id, speaker_id
  - New `wp_digical_session_types` table: type_id, category_name, icon_key, color_gradient, display_order
  - Automatic table creation and schema updates on plugin activation

- **UI/UX Improvements**
  - Session type icons with configurable gradients
  - Modal form with smooth step transitions
  - Participant badges preventing screen jumping with min-height layout
  - Role-based filtering reduces cognitive load
  - Masonry layout for session types with responsive grid

### Improvements
- Per-day submenu pages auto-generate from database (dynamic routing)
- AJAX session save/update with proper error handling
- Speaker role filtering optimized for modal interface
- Database schema updates applied on plugin load

### Technical
- New AJAX handlers in `admin/sessions-ajax-db.php`
- New AJAX handlers in `admin/session-types-ajax-db.php`
- New session type icons in `admin/session-type-icons.php`
- Enhanced `admin/day-plan.php` with modal and filtering logic
- DB schema migrations (auto-applied on plugin activation)
- Improved error logging and AJAX response handling

### Bug Fixes
- Fixed screen jumping issue when role dropdowns appear/disappear (min-height: 200px)
- Improved venue dropdown state persistence across modal reopens
- Enhanced speaker filtering to handle edge cases (no speakers for role, invalid role IDs)

### Known Limitations (Addressed in Future Versions)
- Frontend shortcodes not yet implemented (planned v1.7)
- Multi-track support not yet available (planned v1.8)
- Conflict detection not yet implemented (planned v1.8)
- Import/Export (Excel, JSON) planned for v1.7

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
