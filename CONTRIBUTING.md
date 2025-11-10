# DigiCal – Contributing Guide (v1.6)

Thank you for your interest in contributing to DigiCal! This guide will help you understand our development workflow, coding standards, and how to submit changes.

---

## Getting Started

### Prerequisites
- WordPress 6.0+
- PHP 8.0+
- Git
- Local WordPress installation (via Local, Docker, or similar)

### Local Development Setup
1. Clone the repository into your WordPress plugins folder:
   ```bash
   cd /path/to/wordpress/wp-content/plugins
   git clone https://github.com/digit-obrt/digi-cal.git digical
   ```

2. Activate the plugin in WordPress Admin → Plugins

3. Test in a fresh WordPress instance to avoid conflicts

---

## Development Workflow

### Branching Strategy
- **main:** Stable release versions only
- **develop:** Integration branch for features in progress
- **feature/XXX:** Feature branches (e.g., `feature/sessions-module`)
- **bugfix/XXX:** Bug fixes (e.g., `bugfix/ajax-error`)
- **hotfix/XXX:** Production hotfixes (e.g., `hotfix/security-patch`)

### Creating a Feature Branch
```bash
git checkout develop
git pull origin develop
git checkout -b feature/your-feature-name
```

### Commit Messages
Follow conventional commit style:
```
feat: Add speaker configuration panel
fix: Correct venue address propagation logic
docs: Update README with installation steps
style: Fix linting errors in admin.css
refactor: Simplify AJAX response handling
test: Add unit tests for speakers module
chore: Update dependencies
```

---

## Coding Standards

### PHP
- **Style:** PSR-12 (similar to WordPress standards)
- **Indentation:** 4 spaces (not tabs)
- **Variable naming:** `$snake_case` for variables, `camelCase()` for functions
- **Constants:** `CONSTANT_NAME` in UPPERCASE
- **Comments:** PHPDoc style for functions
  ```php
  /**
   * Retrieves all speakers with optional filtering.
   *
   * @param array $args Query arguments (role, date_range, etc.)
   * @return array Array of speaker objects
   */
  function digical_get_speakers($args = []) {
      // Implementation
  }
  ```

### JavaScript / AJAX
- **Style:** ES6+ with const/let (no var)
- **Indentation:** 2 spaces (matches WordPress core)
- **AJAX calls:** Use WordPress `wp.ajax` if available, else fetch API
- **Scope:** Always wrap code in jQuery ready or use IIFE to avoid conflicts
  ```javascript
  jQuery(document).ready(function($) {
      // Your code here - $ is safe to use
  });
  ```
- **Avoid:** Direct DOM manipulation; use jQuery helpers
- **Variable accessibility:** Ensure all functions called from HTML are globally accessible

### CSS
- **Indentation:** 2 spaces
- **Naming:** BEM-style for component-based styling
- **Colors:** Use CSS variables or hardcoded color constants
- **Responsive:** Mobile-first approach
- **Screen jump prevention:** Use min-height for dynamic content areas
  ```css
  .participants-container {
      min-height: 200px; /* Prevent jumping when elements appear/disappear */
  }
  ```

### Database
- **Table names:** lowercase with underscores (e.g., `wp_digical_speakers`)
- **Column names:** lowercase with underscores
- **Types:** Use appropriate types (varchar, int, text, longtext, timestamp)
- **Indexes:** Add for frequently queried columns
- **UUIDs:** Use varchar(32) for primary keys (UUID format)

---

## File Structure
```
digical/
├── admin/                                    # Admin pages & AJAX handlers
│   ├── days.php                             # Days UI
│   ├── days-ajax-db.php                     # Days AJAX endpoints & DB functions
│   ├── day-plan.php                         # Day Plan modal & session management
│   ├── venues.php                           # Venues UI
│   ├── venues-ajax-db.php                   # Venues AJAX & DB
│   ├── speakers.php                         # Speakers UI
│   ├── speakers-ajax-db.php                 # Speakers AJAX & DB
│   ├── titles-roles-ajax-db.php             # Config AJAX & DB
│   ├── sessions-ajax-db.php                 # Sessions AJAX & DB (NEW v1.6)
│   ├── session-types.php                    # Session Types UI (NEW v1.6)
│   ├── session-types-ajax-db.php            # Session Types AJAX & DB (NEW v1.6)
│   ├── session-type-icons.php               # Session Type icon definitions (NEW v1.6)
│   ├── configuration.php                    # Configuration UI
│   ├── dashboard.php                        # Dashboard overview
│   ├── events.php                           # Events UI (CPT)
│   ├── events-ajax-db.php                   # Events AJAX & DB
│   ├── section-wrapper.php                  # Section HTML wrapper utility
│   └── speaker-photo-upload.php             # Speaker photo handling
├── includes/                                 # Utility files
│   ├── custom-post-types.php               # CPT registration
│   ├── event-helpers.php                   # Event-related helpers
│   ├── helpers.php                         # General helpers
│   ├── meta-boxes.php                      # Meta box registration
│   ├── shortcode-generator.php             # Frontend shortcodes
│   └── github-updater.php                  # GitHub update checker
├── assets/
│   ├── css/
│   │   └── admin.css                       # Admin styling
│   └── js/
│       └── admin.js                        # Admin scripts (if needed)
├── data/                                    # Static data / fixtures
│   └── days.csv                            # Sample data
├── digi-cal.php                            # Main plugin file (entry point)
├── README.md                               # User documentation
├── CHANGELOG.md                            # Version history
├── ROADMAP.md                              # Future plans
├── DATABASE_SCHEMA.md                      # DB documentation
├── UI_STYLE_GUIDE.md                       # Design system
└── CONTRIBUTING.md                         # This file
```

---

## Database Development

### Adding a New Table
1. Create a function `digical_<table>_ensure_table()` in appropriate AJAX file
2. Add table creation SQL with proper indexes
3. Call the function in main `digi-cal.php` during activation
4. Add schema update function `digical_update_<table>_table_schema()` for migrations

### Example Junction Table (Speaker-Session Roles)
```php
function digical_session_roles_ensure_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'digical_session_roles';
    $charset = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(32) NOT NULL,
        role_id INT NOT NULL,
        speaker_id VARCHAR(32) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY session_role_speaker (session_id, role_id, speaker_id),
        KEY by_session (session_id),
        KEY by_role (role_id),
        KEY by_speaker (speaker_id)
    ) $charset;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
```

---

## Testing

### Manual Testing Checklist
- [ ] Test on a fresh WordPress installation
- [ ] Test AJAX operations in Network tab (browser dev tools)
- [ ] Test inline editing (click, Enter, Escape)
- [ ] Test bulk operations (select multiple, delete)
- [ ] Test modal workflows (all 3 steps for sessions)
- [ ] Test with different WordPress user roles
- [ ] Verify database tables are created on activation
- [ ] Check for PHP errors in WordPress error log
- [ ] Test responsive layout on tablet/mobile
- [ ] Verify no console errors in browser DevTools

### Session Modal Testing
- [ ] Step 1: Title validation (required), session type selection
- [ ] Step 2: Time validation (end > start), venue selection, sub-venue dependent dropdown
- [ ] Step 3: Role-based speaker filtering, participant badge display, min-height prevents jump
- [ ] Navigation: Previous/Next buttons work, form validation blocks progress
- [ ] Save: Data persists to DB, modal closes, table refreshes
- [ ] Edit: Load existing session, pre-populate all fields, update works
- [ ] Escape key closes modal (confirm if unsaved)
- [ ] Click overlay closes modal (confirm if unsaved)

### Browser Compatibility
- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest version)

---

## Submitting Changes

### Pull Request Process
1. Push your branch to GitHub:
   ```bash
   git push origin feature/your-feature-name
   ```

2. Open a Pull Request against **develop** branch with:
   - Clear title (e.g., "Add session scheduling UI")
   - Description of changes and motivation
   - Screenshots (if UI changes)
   - Testing notes
   - Database schema changes (if any)

3. Ensure all checks pass:
   - Code style compliance
   - No PHP errors
   - Documented changes
   - Database migrations tested

4. Request review from team members

5. Address feedback and push updates to same branch

### PR Merge Requirements
- ✅ Code review approval
- ✅ All checks passing
- ✅ CHANGELOG.md updated
- ✅ DATABASE_SCHEMA.md updated (if DB changes)
- ✅ UI_STYLE_GUIDE.md updated (if UI changes)
- ✅ Version number updated (if release PR)
- ✅ No merge conflicts

---

## Versioning

DigiCal follows Semantic Versioning (MAJOR.MINOR.PATCH):
- **MAJOR:** Breaking changes (e.g., DB restructure)
- **MINOR:** New features, backwards compatible (v1.3 → v1.4)
- **PATCH:** Bug fixes (v1.4.0 → v1.4.1)

### Updating Version Numbers
1. Update version in `digi-cal.php` (plugin header comment)
2. Add entry to `CHANGELOG.md`
3. Update `README.md` with new features
4. Commit with message: `chore: Bump version to X.Y.Z`

---

## Release Process

### For Minor/Major Releases
1. Create release branch:
   ```bash
   git checkout -b release/v1.7
   ```

2. Update version numbers:
   - `digi-cal.php`
   - `CHANGELOG.md`
   - `README.md` (if necessary)
   - `ROADMAP.md` (mark completed phases)

3. Commit & create PR to main:
   ```bash
   git add .
   git commit -m "Release v1.7"
   git push origin release/v1.7
   ```

4. Merge PR to main after approval

5. Create GitHub Release with:
   - Version tag (v1.7)
   - Changelog excerpt
   - Download link to ZIP

---

## Reporting Issues

Use GitHub Issues with clear titles and descriptions:
- **Bug report:** Describe steps to reproduce, expected vs actual behavior, screenshots
- **Feature request:** Explain use case and desired functionality
- **Question:** Ask in Discussions tab if unsure
- **Database issue:** Include DB table schema if relevant

---

## Code Review Guidelines

When reviewing PRs, check for:
- Adherence to coding standards
- Clear commit messages
- Appropriate test coverage
- No hardcoded values (use constants)
- Security considerations (sanitization, validation, escaping)
- Performance implications (DB queries, AJAX calls)
- Documentation updates (README, CHANGELOG, schema)
- Database backward compatibility
- Modal/form UX consistency

---

## Key Development Notes (v1.6+)

### Session Modal Implementation
- Modal is defined in `admin/day-plan.php` as repeatable template
- Uses 3-step wizard with validation at each step
- Step 3 includes role-based speaker filtering (query DB for speakers by role)
- Participant badges use min-height container to prevent screen jumping
- AJAX save to `sessions-ajax-db.php` handles DB insert/update

### Session Type Icons
- Icons stored as SVG strings in `session-type-icons.php`
- Organized by 5 categories (Keynote, Workshop, Panel, Breakout, Social)
- Each category has 4 icon variations
- Colors applied via CSS gradients from DB `color_gradient` field
- Icons displayed in modal Step 1 as checkbox grid

### Database Relationships
- Sessions link to Days (required), Venues (optional)
- Session Roles junction table (session_id, role_id, speaker_id)
- Speakers have roles via speaker_roles junction table
- Ensure cascading deletes on session removal

### State Persistence
- Venue dropdown selection persists if modal is closed/reopened
- Role selections remain checked during modal workflow
- Speaker dropdowns repopulate based on selected roles
- Use data attributes or JS objects to track state

---

## Questions?
Contact the DIGIT team or open an issue with your question.

---

© 2025 DIGIT
