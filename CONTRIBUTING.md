# DigiCal – Contributing Guide

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
   git clone https://github.com/digit-obrt/digi-cal.git
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
- **Avoid:** Direct DOM manipulation; use WordPress components when possible

### CSS
- **Indentation:** 2 spaces
- **Naming:** BEM-style for component-based styling
- **Colors:** Use CSS variables from style guide
- **Responsive:** Mobile-first approach
  ```css
  /* Desktop-first media query example */
  @media (max-width: 1024px) {
      .table-container { overflow-x: auto; }
  }
  ```

### Database
- **Table names:** lowercase with underscores (e.g., `wp_digical_speakers`)
- **Column names:** lowercase with underscores
- **Types:** Use appropriate types (varchar, int, text, longtext, timestamp)
- **Indexes:** Add for frequently queried columns

---

## File Structure
```
digi-cal/
├── admin/                     # Admin pages & AJAX handlers
│   ├── days.php              # Days UI
│   ├── days-ajax-db.php      # Days AJAX endpoints & DB functions
│   ├── venues.php            # Venues UI
│   ├── venues-ajax-db.php    # Venues AJAX endpoints & DB functions
│   ├── speakers.php          # Speakers UI
│   ├── speakers-ajax-db.php  # Speakers AJAX endpoints & DB functions
│   ├── titles-roles-ajax-db.php  # Config AJAX & DB functions
│   └── section-wrapper.php   # Section HTML wrapper
├── includes/                  # Utility files
│   └── github-updater.php    # GitHub update checker
├── assets/
│   ├── css/
│   │   └── admin.css         # Admin styling
│   └── js/
│       └── admin.js          # Admin scripts (if needed)
├── data/                     # Static data / fixtures
├── digi-cal.php             # Main plugin file (entry point)
├── README.md                # User documentation
├── CHANGELOG.md             # Version history
├── ROADMAP.md               # Future plans
├── DATABASE_SCHEMA.md       # DB documentation
├── UI_STYLE_GUIDE.md        # Design system
└── CONTRIBUTING.md          # This file
```

---

## Testing

### Manual Testing Checklist
- [ ] Test on a fresh WordPress installation
- [ ] Test AJAX operations in Network tab (browser dev tools)
- [ ] Test inline editing (click, Enter, Escape)
- [ ] Test bulk operations (select multiple, delete)
- [ ] Test with different WordPress user roles
- [ ] Verify database tables are created on activation
- [ ] Check for PHP errors in WordPress error log

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

3. Ensure all checks pass:
   - Code style compliance
   - No PHP errors
   - Documented changes

4. Request review from team members

5. Address feedback and push updates to same branch

### PR Merge Requirements
- ✅ Code review approval
- ✅ All checks passing
- ✅ CHANGELOG.md updated
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
3. Commit with message: `chore: Bump version to X.Y.Z`

---

## Release Process

### For Minor/Major Releases
1. Create release branch:
   ```bash
   git checkout -b release/v1.5
   ```

2. Update version numbers:
   - `digi-cal.php`
   - `CHANGELOG.md`
   - `README.md` (if necessary)

3. Commit & create PR to main:
   ```bash
   git add .
   git commit -m "Release v1.5"
   git push origin release/v1.5
   ```

4. Merge PR to main after approval

5. Create GitHub Release (see GitHub Release instructions below)

---

## Reporting Issues

Use GitHub Issues with clear titles and descriptions:
- **Bug report:** Describe steps to reproduce, expected vs actual behavior
- **Feature request:** Explain use case and desired functionality
- **Question:** Ask in Discussions tab if unsure

---

## Code Review Guidelines

When reviewing PRs, check for:
- Adherence to coding standards
- Clear commit messages
- Appropriate test coverage
- No hardcoded values (use constants)
- Security considerations
- Performance implications
- Documentation updates

---

## Questions?
Contact the DIGIT team or open an issue with your question.

---

© 2025 DIGIT
