# DigiCal – Database Schema (v1.6)

---

## Overview
DigiCal uses custom WordPress tables for conference management. All tables are prefixed with the WordPress database prefix (e.g., `wp_` in standard installations).

---

## Tables

### `wp_digical_days`
**Purpose:** Store conference day definitions (dates and time slots).

| Column       | Type        | Constraints | Description |
|--------------|------------|-------------|-------------|
| id           | varchar(32) | PRIMARY KEY | Unique day identifier (UUID-like) |
| date         | varchar(8)  | NOT NULL | Date in format DDMMYYYY |
| start_time   | varchar(5)  | NOT NULL | Start time in format HH:MM |
| end_time     | varchar(5)  | NOT NULL | End time in format HH:MM |
| created_at   | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at   | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- INDEX by_date (date)

**Notes:**
- Time format accepts flexible input (8 or 8:00) but is normalized to HH:MM
- Date locale display (e.g., 01.10.2025) is handled in PHP layer

---

### `wp_digical_venues`
**Purpose:** Store venue information with parent-child hierarchy.

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | varchar(32) | PRIMARY KEY | Unique venue identifier |
| type        | varchar(10) | NOT NULL | 'primary' or 'sub-venue' |
| name        | varchar(255)| NOT NULL | Venue name |
| address     | text        | | Full address (street, city, etc.) |
| parent_id   | varchar(32) | FOREIGN KEY | Reference to parent venue (NULL if primary) |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX by_type_name (type, name)
- INDEX by_parent_id (parent_id)

**Foreign Keys (Logical):**
- parent_id → venues.id (handled in PHP for max compatibility)

**Hierarchy Rules:**
- Primary venue: type = 'primary', parent_id = NULL
- Sub-venue: type = 'sub-venue', parent_id points to primary
- **Address propagation:** When primary address is updated, all sub-venue addresses are updated automatically
- Reverse propagation does NOT occur: editing a sub-venue address does not affect primary or siblings

---

### `wp_digical_speakers`
**Purpose:** Store speaker information and roles.

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | varchar(32) | PRIMARY KEY | Unique speaker identifier |
| title       | varchar(50) | | Title prefix (Prof., Dr., Ing., etc.) |
| first_name  | varchar(100)| NOT NULL | Speaker first name(s) |
| last_name   | varchar(100)| NOT NULL | Speaker last name(s) |
| bio         | longtext    | | Biography or short description |
| email       | varchar(255)| UNIQUE | Speaker email (for future notifications) |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- INDEX by_name (last_name, first_name)
- UNIQUE INDEX email (email)

**Notes:**
- Speaker roles are stored in junction table (`wp_digical_speakers_roles`)
- Email field reserved for future notifications module

---

### `wp_digical_titles`
**Purpose:** Store configurable speaker title options (Prof., Dr., etc.).

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Unique title ID |
| title       | varchar(50) | UNIQUE NOT NULL | Title text (e.g., "Prof.") |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX title (title)

**Default Values:**
- Prof.
- Dr.
- Ing.
- M.Sc.
- Ph.D.

---

### `wp_digical_roles`
**Purpose:** Store speaker role types (Speaker, Moderator, etc.).

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Unique role ID |
| role_name   | varchar(50) | UNIQUE NOT NULL | Role identifier (e.g., "speaker") |
| display_name| varchar(100)| NOT NULL | Display label (e.g., "Speaker") |
| color_code  | varchar(7)  | | Hex color for badge styling (e.g., #2271b1) |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX role_name (role_name)

**Default Values:**
- speaker / Speaker / #2271b1
- moderator / Moderator / #6f42c1

---

### `wp_digical_speakers_roles` (Junction Table)
**Purpose:** Link speakers to roles (many-to-many).

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Junction record ID |
| speaker_id  | varchar(32) | FOREIGN KEY | Reference to speaker |
| role_id     | int         | FOREIGN KEY | Reference to role |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX speaker_role (speaker_id, role_id)
- INDEX by_speaker (speaker_id)
- INDEX by_role (role_id)

**Foreign Keys (Logical):**
- speaker_id → speakers.id
- role_id → roles.id

**Notes:**
- A speaker can have multiple roles (e.g., both "Speaker" and "Moderator")
- Unique constraint prevents duplicate assignments

---

### `wp_digical_session_types` (NEW in v1.6)
**Purpose:** Store session type categories with icons and styling.

| Column       | Type        | Constraints | Description |
|-------------|-------------|-------------|-------------|
| id          | int         | PRIMARY KEY AUTO_INCREMENT | Unique session type ID |
| category_name| varchar(50) | UNIQUE NOT NULL | Type category (Keynote, Workshop, Panel, etc.) |
| icon_key    | varchar(50) | | Icon identifier for rendering |
| color_gradient| varchar(255) | | CSS gradient for visual styling |
| display_order| int         | | Sort order for UI display |
| created_at  | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX category_name (category_name)
- INDEX display_order (display_order)

**Default Categories:**
- Keynote (5 icon variations)
- Workshop (5 icon variations)
- Panel (5 icon variations)
- Breakout (5 icon variations)
- Social (5 icon variations)

**Notes:**
- Icon keys map to SVG icons stored in `session-type-icons.php`
- Color gradients provide visual differentiation in UI
- Display order controls masonry grid arrangement

---

### `wp_digical_sessions` (NEW in v1.6)
**Purpose:** Store conference session information linked to day, venue, and speakers.

| Column         | Type        | Constraints | Description |
|---------------|-------------|-------------|-------------|
| id            | varchar(32) | PRIMARY KEY | Unique session identifier (UUID) |
| day_id        | varchar(32) | FOREIGN KEY | Reference to day (NOT NULL) |
| venue_id      | varchar(32) | FOREIGN KEY | Reference to primary venue |
| title         | varchar(255)| NOT NULL | Session title |
| description   | longtext    | | Session description/abstract |
| start_time    | varchar(5)  | NOT NULL | Start time (HH:MM format) |
| end_time      | varchar(5)  | NOT NULL | End time (HH:MM format) |
| session_level | varchar(20) | | Hierarchy level (master/sub) [future use] |
| created_at    | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |
| updated_at    | timestamp   | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Timestamp of last update |

**Indexes:**
- PRIMARY KEY (id)
- INDEX by_day (day_id)
- INDEX by_venue (venue_id)
- INDEX by_day_venue (day_id, venue_id)
- INDEX by_start_time (start_time)

**Foreign Keys (Logical):**
- day_id → days.id (NOT NULL, cascading on delete)
- venue_id → venues.id (nullable for future flexibility)

**Notes:**
- Times are normalized to HH:MM format
- Session_level field reserved for multi-track/hierarchical session support (v1.8+)
- Sessions are linked to speakers via `wp_digical_session_roles` junction table

---

### `wp_digical_session_roles` (NEW in v1.6 - Junction Table)
**Purpose:** Link sessions to roles and speakers (many-to-many with role association).

| Column         | Type        | Constraints | Description |
|---------------|-------------|-------------|-------------|
| id            | int         | PRIMARY KEY AUTO_INCREMENT | Junction record ID |
| session_id    | varchar(32) | FOREIGN KEY | Reference to session (NOT NULL) |
| role_id       | int         | FOREIGN KEY | Reference to role (NOT NULL) |
| speaker_id    | varchar(32) | FOREIGN KEY | Reference to speaker (NOT NULL) |
| created_at    | timestamp   | DEFAULT CURRENT_TIMESTAMP | Timestamp of creation |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX session_role_speaker (session_id, role_id, speaker_id)
- INDEX by_session (session_id)
- INDEX by_role (role_id)
- INDEX by_speaker (speaker_id)
- INDEX by_session_role (session_id, role_id)

**Foreign Keys (Logical):**
- session_id → sessions.id (cascading delete)
- role_id → roles.id
- speaker_id → speakers.id

**Notes:**
- Links a speaker to a session with a specific role (e.g., Speaker, Keynote, Moderator)
- Unique constraint prevents duplicate speaker+role assignments to same session
- A session can have multiple speakers with different roles
- Enables role-based filtering in day plan modal UI

---

## Planned Tables (Future Versions)

### `wp_digical_tracks` (v1.8+)
```
id (int PK AUTO_INCREMENT)
day_id (varchar 32 FK → days.id)
track_name (varchar 100)
track_color (varchar 7)
sort_order (int)
created_at (timestamp)
```

### `wp_digical_session_types_hierarchy` (v1.8+)
```
id (int PK AUTO_INCREMENT)
parent_session_id (varchar 32 FK → sessions.id)
child_session_id (varchar 32 FK → sessions.id)
relation_type (varchar 50)
created_at (timestamp)
```

---

## Data Integrity Rules

1. **Venue Hierarchy:** Sub-venues must always have a valid parent_id referencing a primary venue.
2. **Address Propagation:** Changes to primary venue address must cascade to all sub-venues (implemented in PHP).
3. **Speaker Uniqueness:** Speaker email is unique (reserved for future use).
4. **Role Assignment:** A speaker can have multiple roles through the junction table.
5. **Session Linking:** Sessions must link to a valid day; venue is optional (future flexibility).
6. **Session-Role-Speaker:** A speaker can only be assigned once per role per session (unique constraint).
7. **Cascading Deletes:** Deleting a session deletes its role assignments; deleting a speaker orphans role assignments.

---

## Migration & Activation

- Tables are created automatically on plugin activation via `register_activation_hook()`
- Schema updates are applied via `digical_update_*_table_schema()` functions called on plugin load
- Existing data is preserved during updates
- Table prefix is dynamically determined by WordPress `$wpdb->prefix`
- New tables in v1.6 are created on first activation

---

## Performance Notes

- All PKs and FKs use varchar(32) for UUID compatibility
- Indexed by name, date, parent, type, and session relationships for fast queries
- Junction tables prevent N+1 queries for speaker/session role assignments
- Composite indexes on day_id + venue_id for efficient session lookups
- Future v1.8 will add track support with additional indexing

---

© 2025 DIGIT
